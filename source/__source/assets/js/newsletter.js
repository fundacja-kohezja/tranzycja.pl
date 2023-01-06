const saveEmailToNewsletter = async (email) => new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/.netlify/functions/newsletter', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');

    xhr.onreadystatechange = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) { resolve(xhr); } else { reject(xhr); }
        }
    };
    xhr.send(JSON.stringify({ email }));
});

export default saveEmailToNewsletter;
