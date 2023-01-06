import saveEmailToNewsletter from '../newsletter';

const pozSurvey = document.getElementById('poz-survey');
pozSurvey.addEventListener('submit', async (ev) => {
    ev.preventDefault();
    const responseElement = document.getElementById('response-message-survey');
    const formData = Object.fromEntries(new FormData(pozSurvey));
    const inputs = pozSurvey.querySelectorAll('input, select, textarea, radio');
    if (!pozSurvey.querySelector('#privacySurvey').checked) {
        responseElement.innerText = 'Zgoda na przetwarzanie danych osobych jest niezbędna';
        responseElement.classList.remove('hidden');
        return;
    }

    const radios = Array.from(pozSurvey.querySelectorAll('input[type="radio"]')).map(({
        checked,
    }) => checked);
    if (!radios.includes(true)) {
        responseElement.innerText = 'Prosimy wybrać ocenę';
        responseElement.classList.remove('hidden');
        return;
    }

    if (formData.privacySurvey) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/.netlify/functions/poz-survey', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Access-Control-Allow-Origin', '*');

        xhr.onreadystatechange = () => { // Call a function when the state changes.
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200 && xhr.responseText === 'ok') {
                    responseElement.innerText = 'Ankieta wypełniona, dziękujemy';
                    responseElement.classList.add('text-green-700', 'dark:text-green-300');
                } else if (xhr.status === 422) {
                    if (xhr.responseText === 'email-taken') {
                        responseElement.innerText = 'Podany adres e-mail został już wykorzystany do ankiety';
                    } else {
                        responseElement.innerText = 'Nie udało się zapisać wyników prosimy upewnić się, że adres e-mail jest poprawny i wszystkie wymagane dane zostały wprowadzone';
                    }
                } else {
                    responseElement.innerText = `Ups, wystąpił nieznany błąd (${xhr.status}), przepraszamy`;
                }
                responseElement.classList.remove('hidden');
                inputs.forEach((input) => xhr.status !== 200 && input.removeAttribute('disabled', false));
            }
        };
        xhr.send(JSON.stringify(formData));
        inputs.forEach((input) => input.setAttribute('disabled', true));
    }

    if (formData.newsletter) {
        await saveEmailToNewsletter(formData.email);
    }
});
