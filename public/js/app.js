function addEventListeners() {
  // let itemCheckers = document.querySelectorAll('article.card li.item input[type=checkbox]');
  // [].forEach.call(itemCheckers, function(checker) {
  //   checker.addEventListener('change', sendItemUpdateRequest);
  // });
  // 
  // let itemCreators = document.querySelectorAll('article.card form.new_item');
  // [].forEach.call(itemCreators, function(creator) {
  //   creator.addEventListener('submit', sendCreateItemRequest);
  // });
  // 
  // let itemDeleters = document.querySelectorAll('article.card li a.delete');
  // [].forEach.call(itemDeleters, function(deleter) {
  //   deleter.addEventListener('click', sendDeleteItemRequest);
  // });
  // 
  // let cardDeleters = document.querySelectorAll('article.card header a.delete');
  // [].forEach.call(cardDeleters, function(deleter) {
  //   deleter.addEventListener('click', sendDeleteCardRequest);
  // });
  // 
  // let cardCreator = document.querySelector('article.card form.new_card');
  // if (cardCreator != null)
  //   cardCreator.addEventListener('submit', sendCreateCardRequest);

  let post_edit = document.querySelectorAll('.make_post_popup');
  [].forEach.call(post_edit, function (post_edit) {
    post_edit.addEventListener('click', logItem);
  });

  let group_add = document.querySelectorAll('.create_group_button');
  [].forEach.call(group_add, function (group_add) {
    group_add.addEventListener('click', makeGroupPopup);
  });


  let create_button = document.querySelector('.make_post .form_button');
  create_button.addEventListener('click', sendCreatePostRequest);

}

function logItem(e) {
  const item = document.querySelector('.make_post');
  console.log(item);
  item.toggleAttribute('hidden');
}

function makeGroupPopup(e) {
  const item = document.querySelector('.make_group');
  console.log(item);
  item.toggleAttribute('hidden');
}

function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function (k) {
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();
  console.log("kdk")
  request.open(method, url, true);
  request.withCredentials = true;
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);
  request.send(encodeForAjax(data));
}

function sendCreatePostRequest(event) {
  let name = document.querySelector('textarea[id=text]').value;
  console.log(name);

  if (name != null)
    sendAjaxRequest('post', '/api/post/', { text: name }, PostAddedHandler);

  event.preventDefault();
}

function PostAddedHandler() {
  console.log(this.status)
  if (this.status != 201) window.location = '/'; // ver dps

  // create alert notification
  console.log("post added");
  logItem(0);
  // talvez dar redirect para a pagina do post
}




function GroupAddedHandler() {
  console.log(this.status)
  if (this.status != 201) window.location = '/'; // ver dps

  // create alert notification
  console.log("group added");
  logItem(0);
  // talvez dar redirect para a pagina do group
}


function sendCreateGroupRequest(event) {

  let name = document.querySelector('textarea[id=group_name]').value;
  let description = document.querySelector('textarea[id=group_description]').value;
  let visibility = true

  if (name == null || description == null || visibility == null)
    return;

  sendAjaxRequest('post', '/api/group/', { name: name, description: description, visibility: visibility }, GroupAddedHandler);

  event.preventDefault();
}




function sendItemUpdateRequest() {
  let item = this.closest('li.item');
  let id = item.getAttribute('data-id');
  let checked = item.querySelector('input[type=checkbox]').checked;

  sendAjaxRequest('post', '/api/item/' + id, { done: checked }, itemUpdatedHandler);
}

function sendDeleteItemRequest() {
  let id = this.closest('li.item').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/item/' + id, null, itemDeletedHandler);
}

function sendCreateItemRequest(event) {
  let id = this.closest('article').getAttribute('data-id');
  let description = this.querySelector('input[name=description]').value;

  if (description != '')
    sendAjaxRequest('put', '/api/cards/' + id, { description: description }, itemAddedHandler);

  event.preventDefault();
}

function sendDeleteCardRequest(event) {
  let id = this.closest('article').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/cards/' + id, null, cardDeletedHandler);
}

function sendCreateCardRequest(event) {
  let name = this.querySelector('input[name=name]').value;

  if (name != '')
    sendAjaxRequest('put', '/api/cards/', { name: name }, cardAddedHandler);

  event.preventDefault();
}

function itemUpdatedHandler() {
  let item = JSON.parse(this.responseText);
  let element = document.querySelector('li.item[data-id="' + item.id + '"]');
  let input = element.querySelector('input[type=checkbox]');
  element.checked = item.done == "true";
}

function itemAddedHandler() {
  if (this.status != 200) window.location = '/';
  let item = JSON.parse(this.responseText);

  // Create the new item
  let new_item = createItem(item);

  // Insert the new item
  let card = document.querySelector('article.card[data-id="' + item.card_id + '"]');
  let form = card.querySelector('form.new_item');
  form.previousElementSibling.append(new_item);

  // Reset the new item form
  form.querySelector('[type=text]').value = "";
}

function itemDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let item = JSON.parse(this.responseText);
  let element = document.querySelector('li.item[data-id="' + item.id + '"]');
  element.remove();
}

function cardDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let card = JSON.parse(this.responseText);
  let article = document.querySelector('article.card[data-id="' + card.id + '"]');
  article.remove();
}

function cardAddedHandler() {
  if (this.status != 200) window.location = '/';
  let card = JSON.parse(this.responseText);

  // Create the new card
  let new_card = createCard(card);

  // Reset the new card input
  let form = document.querySelector('article.card form.new_card');
  form.querySelector('[type=text]').value = "";

  // Insert the new card
  let article = form.parentElement;
  let section = article.parentElement;
  section.insertBefore(new_card, article);

  // Focus on adding an item to the new card
  new_card.querySelector('[type=text]').focus();
}

function createCard(card) {
  let new_card = document.createElement('article');
  new_card.classList.add('card');
  new_card.setAttribute('data-id', card.id);
  new_card.innerHTML = `

  <header>
    <h2><a href="cards/${card.id}">${card.name}</a></h2>
    <a href="#" class="delete">&#10761;</a>
  </header>
  <ul></ul>
  <form class="new_item">
    <input name="description" type="text">
  </form>`;

  let creator = new_card.querySelector('form.new_item');
  creator.addEventListener('submit', sendCreateItemRequest);

  let deleter = new_card.querySelector('header a.delete');
  deleter.addEventListener('click', sendDeleteCardRequest);

  return new_card;
}

function createItem(item) {
  let new_item = document.createElement('li');
  new_item.classList.add('item');
  new_item.setAttribute('data-id', item.id);
  new_item.innerHTML = `
  <label>
    <input type="checkbox"> <span>${item.description}</span><a href="#" class="delete">&#10761;</a>
  </label>
  `;

  new_item.querySelector('input').addEventListener('change', sendItemUpdateRequest);
  new_item.querySelector('a.delete').addEventListener('click', sendDeleteItemRequest);

  return new_item;
}

addEventListeners();


// Home =============================================================================

function updateFeed(feed) {
  if (!document.querySelector('#timeline')) {
    return;
  }

  sendAjaxRequest('get', 'api/post/feed/'+feed, {}, function () {
    let received = JSON.parse(this.responseText);
    
    let timeline = document.querySelector('#timeline');
    timeline.innerHTML = '';
    received.forEach( function (post) {
      timeline.appendChild(createPost(post))
    })

  })
}

function createPost(post) {
  let new_post = document.createElement('article');
  new_post.classList.add('post');
  new_post.innerHTML = `
    <div class="post_head">
      <a href='/profile/${post.owner.username}'><img src="../user.png" alt="" width="50"></a>
      <a href='/profile/${post.owner.username}'>${post.owner.username}</a>
      <a href='/messages'><span class="shareicon">&lt;</span></a>
      <a href='/post/${post.id}'>&vellip;</a>
    </div>

    <div class="post_body">
        <p>${post.text}</p>
        <img src="../post.jpg" alt="" width="400">
    </div>

    <div class="post_footer">

      <p>${post.likes_count}</p>
      <a href="#"><span class="likeicon">&#128077;</span></a>

      <p>${post.comments_count}</p>
      <a href="#"><span class="commenticon">&#128172;</span></a>

      <p>${post.post_date}</p>

    </div>
  `
  return new_post;
}

function updateFeedOnLoad() {
  let feed_filters = document.querySelector('#feed_radio_viral')
  

  if (feed_filters) {
    feed_filters.checked = true
  }

  updateFeed('viral')
}

updateFeedOnLoad()


// =============================================================================
