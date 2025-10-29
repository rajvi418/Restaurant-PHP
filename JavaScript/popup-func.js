function createPopup(id) {
    let popup = document.querySelector(id);
    let overlay = popup.querySelector(".overlay");
    let closebtn = popup.querySelector(".close-btn");
    function openPopup() {
        popup.classList.add("active");
    }
    function closePopup() {
        popup.classList.remove("active");
    }
    overlay.addEventListener("click", closePopup);
    closebtn.addEventListener("click", closePopup);
    return openPopup;

}

let popup = createPopup("#popup");
document.querySelector("#open-popup").addEventListener("click", popup);