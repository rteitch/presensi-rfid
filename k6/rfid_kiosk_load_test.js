import http from 'k6/http';
import { check, sleep } from 'k6';

/**
 * Grafana k6 Load Test — RTH NEXUS RFID Kiosk Peak Hours Simulation
 * Simulates 500 students tapping RFID cards at school entrance during morning peak hours (06:45 - 07:15).
 */

export const options = {
    stages: [
        { duration: '10s', target: 50 },   // Warm-up: 50 students arriving
        { duration: '30s', target: 200 },  // Moderate traffic: 200 students arriving
        { duration: '20s', target: 500 },  // Peak hour burst: 500 students tapping simultaneously
        { duration: '10s', target: 0 },    // Cooldown
    ],
    thresholds: {
        http_req_failed: ['rate<0.01'],   // Error rate should be less than 1%
        http_req_duration: ['p(95)<200'], // 95% of RFID taps should be processed in under 200ms
    },
};

// Sample RFID UIDs to simulate realistic card scanning
const rfidUids = [
    '04A1B2C3', '04X8Y7Z6', '04Z1Z2Z3', '04LOCK123', '04A1B299',
    'A1B2C3D4', 'B2C3D4E5', 'C3D4E5F6', 'D4E5F6A7', 'E5F6A7B8',
];

const BASE_URL = __ENV.BASE_URL || 'http://localhost:8000';
const DEVICE_TOKEN = __ENV.DEVICE_TOKEN || 'kiosk-token-demo';

export default function () {
    const randomUid = rfidUids[Math.floor(Math.random() * rfidUids.length)];

    const payload = JSON.stringify({
        rfid_uid: randomUid,
    });

    const params = {
        headers: {
            'Content-Type': 'application/json',
            'X-Device-Token': DEVICE_TOKEN,
            'Accept': 'application/json',
        },
    };

    const res = http.post(`${BASE_URL}/api/rfid/scan`, payload, params);

    check(res, {
        'status is 200 or 429': (r) => r.status === 200 || r.status === 429,
        'response time < 200ms': (r) => r.timings.duration < 200,
        'has valid json body': (r) => r.json() !== null,
    });

    // Simulate realistic 0.5s to 1.5s delay between card taps per kiosk reader
    sleep(Math.random() * 1 + 0.5);
}
