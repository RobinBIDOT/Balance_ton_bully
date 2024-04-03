
let btnDelContent = document.getElementById('delContent');
btnDelContent.addEventListener('click', (e)=>{
    e.preventDefault()
    let subject = {}
    subject.id =document.getElementById('id_sujet').value
    deleteContent(subject)
})
async function deleteContent(content){
    console.log(content)
    let formData = new FormData();
    Object.keys(content).forEach((key) => {
        formData.append(key, content[key])
    })
    const response = await fetch('http://localhost/balance_ton_bully/php/supprimerSujet.php', {
        method : 'POST',
        body: formData
    })
    let json = await response.json();
    if (json.status == 'success'){
        console.log(json.message)
        alert(json.message)
    }
    console.log(json);
}
