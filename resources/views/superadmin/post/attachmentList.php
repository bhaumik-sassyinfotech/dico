<?php
    if(!empty($post->postAttachment) && count($post->postAttachment) > 0) {
    foreach($post->postAttachment as $attachment) {
?>
<div class="member-wrap files-upload">
    <div class="member-img">
        <img src="<?php echo asset(DEFAULT_ATTACHMENT_IMAGE); ?>" alt="no">
    </div>
    <div class="member-details">
        <h3 class="text-10"><?php echo $attachment->file_name; ?></h3>
        <p>Uploaded By:<a href="#"><?php echo $attachment->attachmentUser->name; ?></a></p>
    </div>
</div>
<?php } }
    else {
        echo "<p class='text-12'>No files uploaded.</p>";
    }
?>