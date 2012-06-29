<?php $this->loadHelper('Candy'); ?>
<?php echo $news['News']['title']; ?>

<?php echo $news_url; ?>

<?php echo $this->Candy->authoring($news['News']['created_on'], $news['Author']); ?>


<?php echo $news['News']['description']; ?>