
 fetch('fetch_profile_posts.php')
 .then(response => response.json())
 .then(posts => {
   const feed = document.querySelector('.feed');

   posts.forEach(async post => {
     const postElement = document.createElement('div');
     postElement.classList.add('post');

     const usernameElement = document.createElement('h3');
     usernameElement.textContent = post.username;
     postElement.appendChild(usernameElement);

     const contentElement = document.createElement('p');
     contentElement.textContent = post.post_content;
     postElement.appendChild(contentElement);

     if (post.post_image) {
        const imageElement = document.createElement('img');
        imageElement.src = post.post_image;
        postElement.appendChild(imageElement);
     }
   
     const timestampElement = document.createElement('span');
     timestampElement.textContent = post.created_at;
     postElement.appendChild(timestampElement);

     feed.appendChild(postElement);
   });
 })
 .catch(error => {
   console.error('Error fetching posts:', error);
 });


 function likePost(postId, username) {
  fetch('like_post.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ postId, username }),
  })
    .then(response => response.json())
    .then(result => {
      
      console.log(result); 
    })
    .catch(error => {
      console.error('Error liking post:', error);
    });
}



function unlikePost(postId,username) {
  fetch('unlike_post.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ postId,username }),
  })
    .then(response => response.json())
    .then(result => {
      
      console.log(result); 
    })
    .catch(error => {
      console.error('Error unliking post:', error);
    });
}


function checkLike(postId) {
  return fetch('check_like.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ postId }),
  })
    .then(response => response.json())
    .then(result => {
      
      return result.liked;
    })
    .catch(error => {
      console.error('Error checking like:', error);
      
      return false;
    });
}