const root = document.documentElement;
const btn = document.getElementById("btnTema");


// tema

btn.addEventListener("click", () => {
    const troca = root.getAttribute("data-tema") === 'troca'

    if (troca){
        root.removeAttribute("data-tema")
    } else {
        root.setAttribute("data-tema", "troca")
    }

})