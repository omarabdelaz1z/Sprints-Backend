<?php 
require_once('../../path.config.php');
require_once(BASE_PATH.'/utils/auth/session.php');
require_once(BASE_PATH.'/utils/pagination.php');
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH.'/controllers/PostController.php');

$database = Database::getInstance();
$connection = $database->getConnection();

$postID = intval($_GET['id']);
$userID = intval(getUserId());

$postController = new PostController($connection);
$post = $postController->findPosts(1, 1, ['post_id' => $postID])[0];
$isLikedByMe = $postController->isLikedBy($userID, $postID);
?>

<?php require_once(BASE_PATH.'/partials/header.php'); ?>
    <!-- Page Content -->
    <!-- Banner Starts Here -->
    <div class="heading-page header-text">
      <section class="page-heading">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="text-content">
                <h4>Post Details</h4>
                <h2><?= $post->getTitle() ?></h2>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    
    <section id='post-details' data-pid="<?= $postID ?>" data-uid="<?= $post->getUserID()?>" class="blog-posts grid-system">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="all-blog-posts">
              <div class="row">
                <div class="col-lg-12">
                  <div class="blog-post">
                    <div class="blog-thumb">
                      <img src="<?= BASE_URL.'/assets/post-images/'.$post->getImage()?>" alt="missing">
                    </div>
                    <div class="down-content">
                      <span><?=$post->getCategory()->getName() ?? ''?></span>
                      <a href="#"><h4><?= $post->getTitle() ?></h4></a>

                      <ul class="post-info">
                        <li><a href="#">Admin</a></li>
                        <li><a href="#"><?= $post->getPublishDate()?></a></li>
                        <li><a href="#"><?= count($post->getComments())?></a></li>
                      </ul>

                      <p><?= $post->getContent() ?></p>
                      <div class="post-options">

                        <div class="row">
                          <div class="col-6">
                            <ul class="post-tags">
                              <li><i class="fa fa-tags"></i></li>
                              <?php foreach($post->getTags() as $tag):?>
                                <li><a href="#"><?=$tag->getName()?></a>,</li>
                              <?php endforeach; ?>
                            </ul>
                          </div>

                          <div class="col-6">
                            <ul class="post-share">
                              <li><i class="fa fa-share-alt"></i></li>
                              <li><a href="#">Facebook</a>,</li>
                              <li><a href="#"> Twitter</a></li>
                            </ul>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span id="like-count"><?= $post->getLikesCount() ?></span> Likes
                    </div>

                    <div class="col-md-6">
                      <button id="like-button" class="btn" type="button" onclick="likePost(<?= intval($post->getID()) ?>)" style="display:<?= $isLikedByMe ? "none" : "block" ?>" >Like</button>
                      <button id="unlike-button" class="btn" type="button" onclick="unlikePost(<?= intval($post->getID()) ?>)" style="display:<?= $isLikedByMe ? "block" : "none" ?>">UnLike</button>
                    </div>
                </div>

                <div class="col-lg-12">
                  <div class="sidebar-item comments">
                    <div class="sidebar-heading">
                      <h2><?= $post->getCommentCount() .' Comments' ?? '0 Comments'?></h2>
                    </div>
                      <ul id='comments-ul'>
                        <?php require_once(BASE_PATH.'/api/comment/render.php') ?>
                      </ul>
                  </div>
                </div>

                </div>
                <div class="col-lg-12">
                  <div class="sidebar-item submit-comment">
                    <div class="sidebar-heading">
                      <h2>Your comment</h2>
                    </div>
                    <div class="content">
                      <form id="comment" method="POST">
                        <div class="row">
                          <div class="col-lg-12">

                            <fieldset>
                              <textarea data-user='<?= $userID ?>' name="message" rows="6" id="message" placeholder="Type your comment"></textarea>
                            </fieldset>

                          </div>
                          <div class="col-lg-12">
                            <fieldset>
                              <button type="submit" id="form-submit" class="main-button">Submit</button>
                            </fieldset>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script>
      $(document).ready(function(){
        $('#comment').on('submit', function(event){
          event.preventDefault();
          const textArea = document.getElementById('message');

          const userID = textArea.dataset.user;
          const postID = document.getElementById('post-details').dataset.pid;
          const content = textArea.value;
          
          console.log(userID, postID, content);

          $.ajax({
            url: '/blog-enhanced/api/comment/create.php',
            data: {
              userID,
              postID,
              content
          },
            method: 'POST',
            success: function(response){
                console.log("Success");
                $("#comments-ul").html(response);
            },
            error: function(error){
              console.log(error);
            }
          });
        });

        $('#delete-comment').on('submit', function(event) {
          event.preventDefault();
          
          const commentID = $(this).parent().data('cid');
          const postID = Number(document.getElementById('post-details').dataset.pid);
          console.log(commentID, postID);

          $.ajax({
            url: '/blog-enhanced/api/comment/delete.php',
            data: {
              postID,
              commentID
            },
            method: 'POST',
            success: function(response){
              console.log("Success");
              $("#comments-ul").html(response);
            },
            error: function(xhr, status){
              console.log(xhr);
            }
          })
        });
      });
    </script>
<?php require_once(BASE_PATH.'/partials/footer.php') ?>