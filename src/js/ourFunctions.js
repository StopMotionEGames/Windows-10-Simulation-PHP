function openURL(url, target) { open(url, target) }
let inputElement = document.querySelector("input[type='search']")
let listElement = document.querySelector(".buttons")
let itemElement = listElement.querySelectorAll(".buttons div.button")

inputElement.addEventListener("input", (e) => {
  let inputed = e.target.value.toLowerCase()
  itemElement.forEach((li) => {
    let text = li.textContent.toLowerCase()
    if (text.includes(inputed)) {
      li.style.display = "flex"
    } else {
      li.style.display = "none"
    }
  })
})