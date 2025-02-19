function clientesForm(){
    var value = document.getElementById('type').value;
    // console.log(value);
    var form = document.getElementById("selectForm");
    if (value == 1){
        form.innerHTML= `
            <label>Nome:</label>
            <input type="text" class="form-control"><br>
            <label>CPF:</label>
            <label text="text" class="form-control"><br>
            <input type="text" class="form-control">
        `;
    } else if (value == 2) {
        form.innerHTML = `
            <h1> deu certo</h1>
        `
    } else {
        form.innerHTML = ""
    }
}