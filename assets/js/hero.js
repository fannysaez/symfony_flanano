function initHero() {
  const leftImage = document.getElementById("left-image");
  const rightImage = document.getElementById("right-image");

  if (!leftImage || !rightImage) {
    return;
  }

  let leftIndex = 0;
  let rightIndex = 17;

  const images = window.heroImages;
  const totalImages = images.length;
  const changeInterval = 1000;

  const updateImage = (imgElement, index) => {
    imgElement.src = images[index];
  };

  setInterval(() => {
    leftIndex = (leftIndex + 1) % totalImages;
    updateImage(leftImage, leftIndex);
  }, changeInterval);

  setTimeout(() => {
    setInterval(() => {
      rightIndex = (rightIndex + 1) % totalImages;
      updateImage(rightImage, rightIndex);
    }, changeInterval);
  }, changeInterval / 2);
}

document.addEventListener("turbo:load", initHero);
document.addEventListener("turbo:render", initHero);
document.addEventListener("DOMContentLoaded", initHero);

document.addEventListener("DOMContentLoaded", () => {
  initHero();
});
