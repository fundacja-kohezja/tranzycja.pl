const https = require('https');
const { validate } = require('deep-email-validator');

const CONTACT_LIST_ID = 154590;

const httpsPost = ({ body, ...options }) => new Promise((resolve, reject) => {
    const req = https.request(
        {
            method: 'POST',
            ...options,
        },

        (res) => {
            const chunks = [];
            res.on('data', (data) => chunks.push(data));
            res.on('end', () => {
                let resBody = Buffer.concat(chunks);
                if (res.headers['content-type'] === 'application/json') {
                    resBody = JSON.parse(resBody);
                }
                resolve(resBody);
            });
        },
    );
    req.on('error', reject);
    if (body) {
        req.write(body);
    }

    req.end();
});

exports.handler = async (event) => {
    const headers = {
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Credentials': 'true',
        'Access-Control-Allow-Methods': 'GET,HEAD,OPTIONS,POST,PUT',
        'Access-Control-Allow-Headers': 'access-control-allow-origin, Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers',
    };

    if (event.httpMethod === 'OPTIONS') {
        return {
            statusCode: 200,
            headers,
        };
    }

    const {
        email,
    } = JSON.parse(event.body) || {};

    const validateEmail = await validate({
        email,
        sender: email,
        validateRegex: true,
        validateMx: true,
        validateTypo: false,
        validateDisposable: true,
        validateSMTP: false,
    });

    if (validateEmail.valid) {
        const body = JSON.stringify({
            contacts: [
                { email },
            ],
        });

        await httpsPost({
            host: 'newsletter.infomaniak.com',
            port: 443,
            path: `/api/v1/public/mailinglist/${CONTACT_LIST_ID}/importcontact`,
            headers: {
                'Content-Type': 'application/json',
                'Content-Length': body.length,
                Accept: '*/*',
                Authorization: `Basic ${process.env.INFOMANIAK_NEWSLETTER_AUTH_TOKEN}`,
            },
            body,
        });

        return {
            statusCode: 200,
            headers,
        };
    }

    return {
        statusCode: 422,
        headers,
    };
};
