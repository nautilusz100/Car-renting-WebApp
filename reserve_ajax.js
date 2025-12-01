
const form = document.querySelector('form');
const success_div = document.querySelector('.success_div');
const error_div = document.querySelector('.error_div');
const success_div_text = document.querySelector('#text_succ');
const error_div_text = document.querySelector('#text_error');
const eltuntet_div = document.querySelector('.eltuntet');

form.addEventListener('submit', onSubmit);
function onSubmit(e) {
    e.preventDefault();
    const formData = new FormData(this);

    const url = new URL(window.location.href);
    const car_id = url.searchParams.get('car_id');
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `reserv.php?car_id=${car_id}`);
    xhr.addEventListener('load', function () 
    {
        //console.log(this.responseText);
        const response = JSON.parse(this.responseText);

        if (response.success) {
            error_div.style.display = 'none'
            success_div.style.display = 'flex'
            success_div_text.innerHTML = response.message;
            eltuntet_div.style.display = 'none';
        } else {
            error_div.style.display = 'flex'
            success_div.style.display = 'none'
            error_div_text.innerHTML = response.message;
            eltuntet_div.style.display = 'none';
        }
    });

    xhr.send(formData);
}
