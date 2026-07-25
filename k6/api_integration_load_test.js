import http from 'k6/http';
import { check, sleep } from 'k6';

/**
 * Grafana k6 Load Test — External Integration API & Rate Limit Test
 * Simulates third-party apps fetching attendance rekap and student history under rate-limiting.
 */

export const options = {
    stages: [
        { duration: '10s', target: 20 },  // Normal third-party API polling
        { duration: '20s', target: 100 }, // High-concurrency API polling
        { duration: '10s', target: 0 },   // Ramp down
    ],
    thresholds: {
        http_req_duration: ['p(95)<300'], // 95% of API requests under 300ms
    },
};

const BASE_URL = __ENV.BASE_URL || 'http://localhost:8000';
const API_KEY = __ENV.API_KEY || 'test-key-123';

export default function () {
    const params = {
        headers: {
            'X-API-Key': API_KEY,
            'Accept': 'application/json',
        },
    };

    // 1. Fetch Attendance Rekap
    const rekapRes = http.get(`${BASE_URL}/api/v1/attendances/rekap?bulan=2026-07`, params);
    check(rekapRes, {
        'rekap status 200 or 429': (r) => r.status === 200 || r.status === 429,
    });

    // 2. Fetch Health Check
    const healthRes = http.get(`${BASE_URL}/api/health`);
    check(healthRes, {
        'health status is 200': (r) => r.status === 200,
        'health database connected': (r) => r.json('services.database') === 'connected',
    });

    sleep(1);
}
