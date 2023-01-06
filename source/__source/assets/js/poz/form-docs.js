import saveEmailToNewsletter from '../newsletter';

const pozEmailForm = document.getElementById('emails-poz-form');
pozEmailForm.addEventListener('submit', async (ev) => {
    ev.preventDefault();
    const responseElement = document.getElementById('response-message');
    const formData = Object.fromEntries(new FormData(pozEmailForm));

    if (!pozEmailForm.querySelector('#privacy').checked) {
        responseElement.innerText = 'Zgoda na przetwarzanie danych osobych jest niezbędna';
        responseElement.classList.remove('hidden');
        return;
    }

    const checkBoxes = Array.from(pozEmailForm.querySelectorAll('input[type="checkbox"]:not(#privacy)')).map(({
        checked,
    }) => checked);
    if (!checkBoxes.includes(true)) {
        responseElement.innerText = 'Prosimy zaznaczyć przynajmniej jedną zgodę';
        responseElement.classList.remove('hidden');
        return;
    }

    if (formData.delivery || formData.templateBook || formData.templateTraining) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/.netlify/functions/poz-email', true);
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onreadystatechange = () => { // Call a function when the state changes.
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200 && xhr.responseText === 'ok') {
                    responseElement.innerText = 'E-mail wysłany';
                    responseElement.classList.add('text-green-700', 'dark:text-green-300');
                } else if (xhr.status === 422) {
                    responseElement.innerText = 'Nie udało się wysłać wiadomości, prosimy upewnić się, że adres e-mail jest poprawny';
                } else {
                    responseElement.innerText = `Ups, wystąpił nieznany błąd (${xhr.status}), przepraszamy`;
                }
                responseElement.classList.remove('hidden');
                pozEmailForm.querySelectorAll('input').forEach((input) => xhr.status !== 200 && input.removeAttribute('disabled', false));
            }
        };
        xhr.send(JSON.stringify(formData));
        pozEmailForm.querySelectorAll('input').forEach((input) => input.setAttribute('disabled', true));
    }

    if (formData.newsletter) {
        await saveEmailToNewsletter(formData.email);
    }
});
