<?php
    $users = [
            ["username" => "yarondassonneville"],
            ["username" => "thomasthaens"]
        ];
    
    $posts = [
            ["p_image" => "https://scontent-amt2-1.cdninstagram.com/t51.2885-15/e35/12783885_191452124560709_1126048559_n.jpg", "username" => "gent", "time_post" => "55 m.", "post_image" => "https://scontent-amt2-1.cdninstagram.com/t51.2885-15/e35/12783885_191452124560709_1126048559_n.jpg?ig_cache_key=MTIwMTM4NDk0NTc3MjQyMTE3Nw%3D%3D.2", "post_text" => "De jongen en zijn sojasausja #weekend #sushi #sushishopantwerp #boyfwiendtime"],
        ["p_image" => "https://scontent-ams3-1.cdninstagram.com/t51.2885-19/10665411_1541365642763635_2097569741_a.jpg", "username" => "jackandjones_official", "time_post" => "2 u.", "post_image" => "https://scontent-ams3-1.cdninstagram.com/t51.2885-15/s1080x1080/e15/fr/12748195_1707619106181407_437382664_n.jpg?ig_cache_key=MTE5MDUwMTg3MTQzNDQwNDg3Mw%3D%3D.2", "post_text" => "Sneaker kind of day? We suggest you check out these! @jackandjones_footwear #styleno12104235 #classicsneakers #lightweight #jjfootwear #jackandjones #menssneakers"]
        ];
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IMDStagram</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/reset.css">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header>
        <div class="innerHeader">
        <a href="timeline.php" class="logoInsta">IMDstagram Home</a>
        
        <div class="search">
            <input placeholder="Zoeken" value type="text" class="inputSearch">
            <!--<div class="realBox">
            <div class="searchLogo"></div>
            <span class="searchText">Zoeken</span>
            </div>-->
        </div>
        
        <div class="profileName">
            <a href="account.php"><?php echo $users[0]["username"] ?></a></div>
        </div>
        <div class="clearfix"></div>
    </header>

    <section class="timeline">

        <a href="postImage.php" class="uploadImage">
            <div class="photoUpload"></div>
            <p>Post foto</p>
        </a>

       <?php foreach($posts as $post): ?>
        <div class="instaPost">
            <div class="instaPost_header">
                <div class="ip_header_profile">
                    <img src="<?php echo $post["p_image"] ?>" alt="<?php echo $post["username"] ?>" class="postProfileImage">
                    <p><?php echo $post["username"] ?></p>
                </div>
                <div class="instaPost_timeAgo">
                    <?php echo $post["time_post"] ?>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="instaPost_image">
                <img src="<?php echo $post["post_image"] ?>" alt="">
            </div>
            <div class="instaPost_body">
               <div class="ip_body_content">
                <div class="ip_body_likes">
                    <a href="">viktoriagaa</a> and <a href="">viktoriagaa</a> vinden dit leuk.
                </div>
                <div class="ip_body_textContent">
                    <a href="" class="authorPost"><?php echo $post["username"] ?></a>
                    <p class="postText"><?php echo $post["post_text"] ?></p>
                    <div class="postReactions"></div>
                </div>
            </div>
            <div class="likeAndReact">
                <div class="like">
                    
                </div>
                <div class="react">
                    <input type="text" placeholder="Een reactie toevoegen...">
                </div>
                <div class="more">
                    
                </div>
                <div class="clearfix"></div>
            </div>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="insta_loadMore">Load more</div>
    </section>
</body>
</html>