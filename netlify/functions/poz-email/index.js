const nodemailer = require('nodemailer');
const { validate } = require('deep-email-validator');

const getElementWhenTemplateBookSelected = () => `<li style="box-sizing: border-box;border: 0 solid #e2e8f0;margin-top: 1rem;margin-bottom: 1rem;">
    <a href="/publikacje/list-otwarty-ptppd"
        style="box-sizing: border-box;border: 0 solid #e2e8f0;background-color: rgba(226,232,240,1);color: rgba(217,119,155,1);text-decoration: none;font-weight: 600;border-bottom-width: 0;border-color: currentColor;--text-opacity: 1;position: relative;z-index: 1;--bg-opacity: 1;border-radius: .5rem;display: flex;flex-grow: 1;padding-left: 1rem;padding-right: 1rem;padding-top: 1.5rem;padding-bottom: 1.5rem;word-wrap: break-word;overflow-wrap: break-word;">
        <article style="box-sizing: border-box;border: 0 solid #e2e8f0;">
            <h2
                style="box-sizing: border-box;border: 0 solid #e2e8f0;margin: 0;font-size: 1.5rem;font-weight: 800;line-height: 1.25;margin-bottom: 0;margin-top: 0;--text-opacity: 1;color: rgba(67,65,144,1);font-family: Raleway,Arial,sans-serif;letter-spacing: .025em;">
                PDF książek i wzór ulotek (pierwszy checkbox)
            </h2>
            <p
                style="box-sizing: border-box;border: 0 solid #e2e8f0;margin: 0;margin-top: .75rem;margin-bottom: 0;font-weight: 400;font-size: .925rem;--text-opacity: 1;color: rgba(74,85,104,1);">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce vitae elit et diam
                maximus viverra. Aliquam eget volutpat sem. Sed non egestas dui, nec facilisis justo. In
                dapibus bibendum eros a ultricies. Phasellus vel dignissim nisi. Vestibulum at dictum
                eros, non consequat felis. Vestibulum nisl orci, porttitor commodo pulvinar in, dictum
                vel magna. Curabitur id tempor felis. Sed congue interdum mollis. Etiam hendrerit
                gravida magna, quis fermentum justo ullamcorper in. Etiam quis pellentesque sem, at
                commodo mi. Nulla elit lectus, pellentesque sed urna ac, imperdiet volutpat ipsum.
                Pellentesque eu leo at tortor efficitur luctus. Maecenas orci nunc, dapibus in
                vestibulum ut, cursus eu eros.
                <b
                    style="box-sizing: border-box;border: 0 solid #e2e8f0;font-weight: 700;display: inline-block;">Kliknij by pobrać</b>
            </p>
        </article>
    </a>
</li>`;

const getElementWhenTemplateTrainingSelected = () => `<li style="box-sizing: border-box;border: 0 solid #e2e8f0;margin-top: 1rem;margin-bottom: 1rem;">
    <a href="/publikacje/list-otwarty-ptppd"
        style="box-sizing: border-box;border: 0 solid #e2e8f0;background-color: rgba(226,232,240,1);color: rgba(217,119,155,1);text-decoration: none;font-weight: 600;border-bottom-width: 0;border-color: currentColor;--text-opacity: 1;position: relative;z-index: 1;--bg-opacity: 1;border-radius: .5rem;display: flex;flex-grow: 1;padding-left: 1rem;padding-right: 1rem;padding-top: 1.5rem;padding-bottom: 1.5rem;word-wrap: break-word;overflow-wrap: break-word;">
        <article style="box-sizing: border-box;border: 0 solid #e2e8f0;">
            <h2
                style="box-sizing: border-box;border: 0 solid #e2e8f0;margin: 0;font-size: 1.5rem;font-weight: 800;line-height: 1.25;margin-bottom: 0;margin-top: 0;--text-opacity: 1;color: rgba(67,65,144,1);font-family: Raleway,Arial,sans-serif;letter-spacing: .025em;">
                Materiały wdrożeniowe (Drugi checkbox)
            </h2>
            <p
                style="box-sizing: border-box;border: 0 solid #e2e8f0;margin: 0;margin-top: .75rem;margin-bottom: 0;font-weight: 400;font-size: .925rem;--text-opacity: 1;color: rgba(74,85,104,1);">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce vitae elit et diam
                maximus viverra. Aliquam eget volutpat sem. Sed non egestas dui, nec facilisis justo. In
                dapibus bibendum eros a ultricies. Phasellus vel dignissim nisi. Vestibulum at dictum
                eros, non consequat felis. Vestibulum nisl orci, porttitor commodo pulvinar in, dictum
                vel magna. Curabitur id tempor felis. Sed congue interdum mollis. Etiam hendrerit
                gravida magna, quis fermentum justo ullamcorper in. Etiam quis pellentesque sem, at
                commodo mi. Nulla elit lectus, pellentesque sed urna ac, imperdiet volutpat ipsum.
                Pellentesque eu leo at tortor efficitur luctus. Maecenas orci nunc, dapibus in
                vestibulum ut, cursus eu eros.
                <b
                    style="box-sizing: border-box;border: 0 solid #e2e8f0;font-weight: 700;display: inline-block;">Kliknij by pobrać</b>
            </p>
        </article>
    </a>
</li>`;

const getElementWhenDeliverySelected = () => `<li style="box-sizing: border-box;border: 0 solid #e2e8f0;margin-top: 1rem;margin-bottom: 1rem;">
    <a href="/publikacje/list-otwarty-ptppd"
        style="box-sizing: border-box;border: 0 solid #e2e8f0;background-color: rgba(226,232,240,1);color: rgba(217,119,155,1);text-decoration: none;font-weight: 600;border-bottom-width: 0;border-color: currentColor;--text-opacity: 1;position: relative;z-index: 1;--bg-opacity: 1;border-radius: .5rem;display: flex;flex-grow: 1;padding-left: 1rem;padding-right: 1rem;padding-top: 1.5rem;padding-bottom: 1.5rem;word-wrap: break-word;overflow-wrap: break-word;">
        <article style="box-sizing: border-box;border: 0 solid #e2e8f0;">
            <h2
                style="box-sizing: border-box;border: 0 solid #e2e8f0;margin: 0;font-size: 1.5rem;font-weight: 800;line-height: 1.25;margin-bottom: 0;margin-top: 0;--text-opacity: 1;color: rgba(67,65,144,1);font-family: Raleway,Arial,sans-serif;letter-spacing: .025em;">
                Zgoda na kontakt i wydrukowane materiały (Trzeci checkbox)
            </h2>
            <p
                style="box-sizing: border-box;border: 0 solid #e2e8f0;margin: 0;margin-top: .75rem;margin-bottom: 0;font-weight: 400;font-size: .925rem;--text-opacity: 1;color: rgba(74,85,104,1);">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce vitae elit et diam
                maximus viverra. Aliquam eget volutpat sem. Sed non egestas dui, nec facilisis justo. In
                dapibus bibendum eros a ultricies. Phasellus vel dignissim nisi. Vestibulum at dictum
                eros, non consequat felis. Vestibulum nisl orci, porttitor commodo pulvinar in, dictum
                vel magna. Curabitur id tempor felis. Sed congue interdum mollis. Etiam hendrerit
                gravida magna, quis fermentum justo ullamcorper in. Etiam quis pellentesque sem, at
                commodo mi. Nulla elit lectus, pellentesque sed urna ac, imperdiet volutpat ipsum.
                Pellentesque eu leo at tortor efficitur luctus. Maecenas orci nunc, dapibus in
                vestibulum ut, cursus eu eros.
                <b
                    style="box-sizing: border-box;border: 0 solid #e2e8f0;font-weight: 700;display: inline-block;">Kliknij by pobrać</b>
            </p>
        </article>
    </a>
</li>`;

const getMainMailTemplate = (extraElements) => `
<html
    style="box-sizing: border-box;border: 0 solid #e2e8f0;line-height: 1.5;-webkit-text-size-adjust: 100%;font-family: Inter,Arial,sans-serif;--scroll-behavior: smooth;scroll-behavior: smooth;font-size: 15px;">

<body
    style="box-sizing: border-box;border: 0 solid #e2e8f0;margin: 0;font-size: 15px;font-family: Inter,Arial,sans-serif;line-height: 1.6;color: rgba(45,55,72,1);visibility: visible!important;">
    <div style="box-sizing: border-box;border: 0 solid #e2e8f0;padding-top: 2rem;width: 100%;">
        <main
            style="box-sizing: border-box;border: 0 solid #e2e8f0;display: block;width: 100%;margin-left: auto;margin-right: auto;max-width: 72rem;padding-left: 1.5rem;padding-right: 1.5rem;padding-top: 3rem;padding-bottom: 3rem;">
            <div style="box-sizing: border-box;border: 0 solid #e2e8f0;text-align: center;">
                <h1
                    style="box-sizing: border-box;border: 0 solid #e2e8f0;font-size: 15px;margin: 0;font-weight: inherit;color: rgba(63,82,165,1);line-height: 1.25;margin-bottom: 0;margin-top: 0;font-family: Raleway,Arial,sans-serif;letter-spacing: .025em;font-weight: 800">

                    <span
                        style="font-size: 2.625rem;box-sizing: border-box;border: 0 solid #e2e8f0;vertical-align: middle;">POZ
                        Włącznik</span>
                </h1>
                <p
                    style="box-sizing: border-box;border: 0 solid #e2e8f0;margin: 0;margin-top: .75rem;margin-bottom: .75rem;font-family: Raleway,Arial,sans-serif;font-weight: 600;font-size: 1.125rem;color: rgba(113,128,150,1);letter-spacing: .05em;">
                    Materiały i informacje
                </p>
            </div>

            <ul
                style="box-sizing: border-box;border: 0 solid #e2e8f0;margin: 0;padding: 0;list-style: none;margin-top: 1rem;margin-bottom: 1rem;padding-left: 0;list-style-type: none;padding-top: .5rem;padding-bottom: .5rem;">
                ${extraElements.filter((v) => v).join(' ')}    
            </ul>
        </main>
    </div>
</body>
</html>`;

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
        firstName,
        email: to,
        templateBook,
        templateTraining,
        delivery,
        privacy,
    } = JSON.parse(event.body) || {};

    const validateEmail = await validate({
        email: to,
        sender: to,
        validateRegex: true,
        validateMx: true,
        validateTypo: false,
        validateDisposable: true,
        validateSMTP: false,
    });

    if (firstName && validateEmail.valid
        && (templateBook || templateTraining || delivery) && privacy
    ) {
        const transporter = nodemailer.createTransport({
            host: 'mail.infomaniak.com',
            port: 465,
            secure: true,
            auth: {
                user: process.env.EMAIL_USER,
                pass: process.env.EMAIL_USER_PASS,
            },
        });

        await transporter.sendMail({
            from: 'admin@kohezja.org',
            to,
            subject: 'POZ Włącznik',
            html: getMainMailTemplate([
                templateBook && getElementWhenTemplateBookSelected(),
                templateTraining && getElementWhenTemplateTrainingSelected(),
                delivery && getElementWhenDeliverySelected(),
            ]),
        });

        return {
            statusCode: 200,
            body: 'ok',
            headers,
        };
    }
    return {
        statusCode: 422,
        headers,
    };
};
