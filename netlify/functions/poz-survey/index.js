const { validate } = require('deep-email-validator');
const faunadb = require('faunadb');

const q = faunadb.query;
const {
    FAUNADB_SECRET, FAUNADB_DOMAIN, FAUNADB_PORT, FAUNADB_SCHEME,
} = process.env;

exports.handler = async (event) => {
    const client = new faunadb.Client({
        secret: FAUNADB_SECRET,
        domain: FAUNADB_DOMAIN,
        port: FAUNADB_PORT,
        scheme: FAUNADB_SCHEME,
    });

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
        firstNameSurvey: firstName,
        emailSurvey: email,
        privacySurvey: privacy,
        ratingSurvey: rating,
        placeSurvey: place,
        commentSurvey: comment,
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

    if (firstName && validateEmail.valid && privacy && rating && place) {
        try {
            await client.query(
                q.Create(
                    q.Collection('answers'),
                    {
                        data: {
                            firstName,
                            email,
                            rating: parseInt(rating, 10),
                            place: parseInt(place, 10),
                            ...!!comment.trim() && { comment },
                        },
                    },
                ),
            );
            return {
                statusCode: 200,
                body: 'ok',
                headers,
            };
        } catch (e) {
            if (e.name === 'BadRequest') {
                return {
                    statusCode: 422,
                    body: 'email-taken',
                    headers,
                };
            }
            throw Error(e);
        }
    }
    return {
        statusCode: 422,
        headers,
    };
};
