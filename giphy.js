const giphyIcon = document.querySelector('.giphy-icon');
giphyIcon.addEventListener('click', openGiphyModal);


function openGiphyModal() {
  const modal = document.querySelector('.giphy-modal');
  modal.style.display = 'block';
  
  const apiKey = 'OeXnZUCnTL66VXADW2LdUz7ZP9BlTfR4';
  const url = `https://api.giphy.com/v1/gifs/trending?api_key=${apiKey}`;

  fetch(url)
    .then(response => response.json())
    .then(data => {
      const gifsContainer = document.querySelector('.gifs-container');
      gifsContainer.innerHTML = '';

      data.data.forEach(gif => {
        const img = document.createElement('img');
        img.src = gif.images.fixed_height.url;
        img.alt = gif.title;
        img.addEventListener('click', () => {
         
        const selectedGifUrlInput = document.querySelector('#selected-gif-url');
        selectedGifUrlInput.value = gif.images.original.url;

          //Chiusura modale
          modal.style.display = 'none';
        });

        gifsContainer.appendChild(img);
      });
    })
    .catch(error => console.error(error)); 
}



function closeModal() {
    const modal = document.querySelector('.giphy-modal');
    modal.style.display = 'none';
  }
  
 
  const closeBtn = document.querySelector('.close-modal');
  closeBtn.addEventListener('click', closeModal);
  