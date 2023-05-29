
 fetch('fetch_posts.php')
 .then(response => response.json())
 .then(posts => {

   const feed = document.querySelector('.feed');   

   posts.forEach(async post => {
     const postElement = document.createElement('div');
     postElement.classList.add('post');
    
     //Pulsante rimozione post 
     const removeButton = document.createElement('i');
     removeButton.classList.add('fas', 'fa-times', 'remove-button');
     removeButton.style.marginLeft = '98%'; 
     removeButton.style.cursor = 'pointer';     
     postElement.appendChild(removeButton);    
     removeButton.addEventListener('click', () => {
          removePost(post.id);
          postElement.remove();
     });     
     
     //Username creatore post 
     const usernameElement = document.createElement('h3');
     usernameElement.textContent = post.username;
     postElement.appendChild(usernameElement);

     //Testo 
     const contentElement = document.createElement('p');
     contentElement.textContent = post.post_content;
     postElement.appendChild(contentElement);

     //Immagine/gif pubbicata
     if (post.post_image) {     
       const isGif = post.post_image.includes('.gif');
     if (isGif) {
       const gifUrl = decodeURIComponent(post.post_image);       
       const gifElement = document.createElement('img');
       gifElement.src = gifUrl;
       gifElement.setAttribute('autoplay', 'autoplay');
       gifElement.setAttribute('loop', 'loop');
       gifElement.classList.add('gif');
       postElement.appendChild(gifElement);
      } else{
        const imageElement = document.createElement('img');
        imageElement.src = post.post_image;
        postElement.appendChild(imageElement);
      }
    }     
    
    // Pulsante like
    const likeIcon = document.createElement('i');
    likeIcon.classList.add('fas', 'fa-thumbs-up');
    likeIcon.style.cursor = 'pointer';  
    //Gestione like    
    let isLiked = await checkLike(post.id);    
    if (isLiked) {
      likeIcon.classList.add('liked');
    }    
    likeIcon.addEventListener('click', () => {
      if (isLiked) {
        unlikePost(post.id, post.username);
        likeIcon.classList.remove('liked');
        isLiked = false;
      } else {
        likePost(post.id, post.username);
        likeIcon.classList.add('liked');
        isLiked = true;
      }
    });   
    postElement.appendChild(likeIcon);


   //Pulsante commenti    
   const commentIcon = document.createElement('i');
   commentIcon.classList.add('fas', 'fa-comment');
   commentIcon.style.cursor = 'pointer';
   commentIcon.style.position = 'relative';
   commentIcon.style.top = '-15px';
   commentIcon.style.marginLeft = '25px';
   
   commentIcon.addEventListener('click', () => {
    //Evito di fare spuntare la barra di input più di una volta
    const existingCommentInput = postElement.querySelector('.comment-input');      
    if (existingCommentInput) {
      existingCommentInput.focus();
      return;
    }
     const commentInput = document.createElement('input');
     commentInput.classList.add('comment-input');
     commentInput.setAttribute('type', 'text');
     commentInput.setAttribute('placeholder', 'Enter your comment...');
     postElement.appendChild(commentInput);
     commentInput.focus();

     //Gestione commenti
     commentInput.addEventListener('keydown', event => {
       if (event.key === 'Enter') {
         const commentContent = commentInput.value.trim();
         if (commentContent !== '') {
           submitComment(post.id, commentContent, postElement);
         }
       }
     });
   });

   postElement.appendChild(commentIcon);

   //Prende i commenti dal database e li mette nel feed
   fetchComments(post.id, postElement);

    
    
    // Data 
    const timestampElement = document.createElement('span');
    timestampElement.textContent = post.created_at;
    timestampElement.style.marginLeft = '77%';
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


function removePost(postId) {
  fetch('remove_post.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ postId }),
  })
    .then(response => response.json())
    .then(result => {
      if (result.status === 'success') {
        console.log(result.message);
      } else {
        console.error(result.message);
      }
    })
    .catch(error => {
      console.error('Error removing post:', error);
    });
}



function fetchComments(postId, postElement) {
  fetch('fetch_comments.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ postId }),
  })
    .then(response => response.json())
    .then(comments => {
      const commentsContainer = document.createElement('div');
      commentsContainer.classList.add('comments-container');

      comments.forEach(comment => {
        const commentContainer = document.createElement('div');
        commentContainer.classList.add('comment');

        const commentAuthor = document.createElement('h3');
        commentAuthor.textContent = comment.username;
        commentContainer.appendChild(commentAuthor);

        const commentContent = document.createElement('p');
        commentContent.textContent = comment.comment_content;
        commentContainer.appendChild(commentContent);

        commentsContainer.appendChild(commentContainer);
      });

      postElement.appendChild(commentsContainer);
    })
    .catch(error => {
      console.error('Error fetching comments:', error);
    });
}


function submitComment(postId, commentContent, postElement) {
  fetch('submit_comment.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ postId, commentContent }),
  })
    .then(response => response.json())
    .then(result => {
      if (result.status === 'success') {
        
        const commentContainer = document.createElement('div');
        commentContainer.classList.add('comment');
        
        const authorUsernameElement = document.createElement('h3');
        authorUsernameElement.textContent = result.authorUsername;
        
        const commentContentElement = document.createElement('p');
        commentContentElement.textContent = commentContent;     

        commentContainer.appendChild(authorUsernameElement);
        commentContainer.appendChild(commentContentElement);
        
        postElement.querySelector('.comments-container').appendChild(commentContainer);

       
        const commentInput = postElement.querySelector('.comment-input');
        commentInput.value = '';
      } else {
        console.error(result.message);
      }
    })
    .catch(error => {
      console.error('Error submitting comment:', error);
    });
}