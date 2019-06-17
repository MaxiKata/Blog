<?php

$title = 'Bienvenue sur le blog de Maxime Guilhem';
require_once '../View/layout.php'; ?>


<body class="d-flex flex-column h-100">
    <?php require '../View/header.php'; ?>
    <h1><?= filter_var($title, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></h1>
    <?= filter_var($alert, FILTER_UNSAFE_RAW); ?>
    <section class="container mx-auto mb-4">
        <div class="h1 font-italic text-center">
            <span>J'aime le saucisson, mais le code c'est bien aussi !</span>
            <div class="h-300"><img class="h-100" src="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/Public/img/logo.png" alt="Logo du site" /></div>
        </div>
        <br>
        <h2>Description</h2>
        <p class="text-justify">Et j'ajoute un petit de Lorem Ipsum pour meubler. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla lacinia dignissim lectus, et lobortis tortor ornare vel. Pellentesque et fermentum ex, nec mattis leo. Sed efficitur felis ornare luctus dapibus. Integer pulvinar tincidunt nisl, vel sagittis nisi consectetur sit amet. Maecenas vitae nisl mauris. Aenean varius dolor suscipit turpis laoreet, vitae feugiat diam efficitur. Donec maximus sem nulla, vitae pharetra quam finibus non. Vivamus eget dignissim nulla. Ut nec arcu a massa ornare luctus tincidunt in neque. Curabitur cursus arcu id libero ullamcorper scelerisque. Vestibulum et augue gravida, ornare ipsum ut, pretium odio. Maecenas nunc eros, pellentesque auctor urna ut, sodales egestas magna. Duis hendrerit ornare pulvinar. Suspendisse sed fermentum purus, quis facilisis nunc.</p>
    </section>

    <section class="container mx-auto mb-4">
        <h2>Retrouvez toutes les categories et leurs derniers articles</h2>

        <ul class="homeList">
            <?php foreach($categories as $category){
                $color = $category['color'];
                $cat = $category['category'];
                $nbPost = $category['nbPost']?>
                <li style="background-color: <?= filter_var($color, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>">
                    <a class="trapezoid" href="index.php?access=blog!category&category=<?= filter_var($cat, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>">
                        <div class="absolute-bloc">
                            <div class="position-relative d-flex">
                                <button class="category-button">Visitez</button>
                                <span class="triangle-left mr-3"></span>
                                <h2 class="text my-auto"><?= filter_var($cat, FILTER_SANITIZE_FULL_SPECIAL_CHARS);  ?> <?= filter_var($nbPost, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?> article(s)</h2>
                            </div>
                        </div>
                    </a>
                    <?php foreach ($articles as $article){
                        if($category['category'] == $article['category']){
                            $aId = $article['id'];
                            $aTitle = $article['title'];
                            $aDate = $article['datePostUpdate_fr'];
                            $aContent = $article['content']; ?>
                            <a class="last-article d-flex" href="index.php?id=<?= filter_var($aId, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>&access=blog!read">
                                <div class="my-auto d-inline opacity-delay">
                                    <div class="mx-0">
                                        <h3>
                                            <?= filter_var($aTitle, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?>
                                            <em><?= filter_var($aDate, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></em>
                                        </h3>
                                        <p><?= filter_var($aContent, FILTER_SANITIZE_FULL_SPECIAL_CHARS); ?></p>
                                    </div>
                                </div>
                                <button class="category-button">Lire</button>
                                <span class="triangle-right"></span>
                            </a>
                            <?php break;
                        }
                    } ?>
                </li>

            <?php } ?>
        </ul>
    </section>
    
    <section class="container mx-auto my-5">
        <h2>Demandez que l'on vous recontacte</h2>
        <form class="my-2 py-3 border border-primary rounded" method="post" action="<?= filter_var($directory, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?>/index.php?access=contact!email">
            <table class="col-8 mx-auto">
                <tr>
                    <td valign="top">
                        <label for="first_name">First Name *</label>
                    </td>
                    <td valign="top">
                        <input  type="text" name="first_name" maxlength="50" size="30">
                    </td>
                </tr>
                <tr>
                    <td valign="top"">
                    <label for="last_name">Last Name *</label>
                    </td>
                    <td valign="top">
                        <input  type="text" name="last_name" maxlength="50" size="30">
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="email">Email Address *</label>
                    </td>
                    <td valign="top">
                        <input  type="email" name="email_from" maxlength="80" size="30">
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="telephone">Telephone Number</label>
                    </td>
                    <td valign="top">
                        <input  type="text" name="telephone" maxlength="30" size="30">
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="comments">Comments *</label>
                    </td>
                    <td valign="top">
                        <textarea  name="comments" maxlength="1000" cols="29" rows="6"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <button class="btn btn-success" type="submit" name="email">Envoyer</button>
                    </td>
                </tr>
            </table>
        </form>
    </section>

    <?php require '../View/footer.php'; ?>

</body>



