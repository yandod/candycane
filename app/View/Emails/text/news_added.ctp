<?php $this->loadHelper('Candy'); ?>
<?php echo $news['News']['title']; ?>

<?php echo $news_url; ?>

<?php echo $this->Candy->format_username($news['Author']); ?>


<?php echo $news['News']['description']; ?>