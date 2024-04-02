// async function getMessage(){
//    const response = await fetch('http://localhost/balance_ton_bully/php/message.php');
//     let json = await response.json();
//     console.log(json.message)
// }
let btnNewName = document.getElementById('newName')
btnNewName.addEventListener("click", (e) => {
    e.preventDefault();
    let data = {};
    data.name = document.getElementById('inputName').value
    data.firstName = document.getElementById('newFName').value
    data.mail = document.getElementById('newMail').value
    data.userName = document.getElementById('newUName').value
    postUserData(data);
});

async function postUserData(data){
    console.log(data)
    let formData = new FormData();
    Object.keys(data).forEach((key) => {
        formData.append(key, data[key])
    })
    const response = await fetch('http://localhost/balance_ton_bully/php/userMod.php', {
        method : 'POST',
        body: formData
    })
    let json = await response.json();
    if (json.status === 'success'){
        console.log(json.message)
        alert(json.message)
    }
    console.log(json);
}
