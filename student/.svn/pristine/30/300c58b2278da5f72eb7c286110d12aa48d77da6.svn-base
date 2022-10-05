<div class="page-header mt-0">
    <?php if (!empty($course)): ?>
        <a href="<?php echo base_url("forum/post/course/{$course->id}") ?>" class="btn btn-primary btn-block"><i class="fe fe-plus mr-2"></i>New Post</a>
    <?php else: ?>
        <a href="<?php echo base_url('forum/post') ?>" class="btn btn-primary btn-block"><i class="fe fe-plus mr-2"></i>New Post</a>
    <?php endif ?>
</div>
<div class="card card-profile">
    <div class="card-body text-center">
        <div class="forum-avatar <?php echo $profile->avatar_color ?? 'bg-cyan' ?> mx-auto my-4">
            <i class="fas fa-<?php echo $profile->avatar_icon ?? 'smile' ?>"></i>
        </div>
        <?php if (empty($profile)): ?>
            <p>To get started with the forum, create a user profile.</p>
            <a href="<?php echo base_url('forum/profile') ?>" class="btn btn-sm btn-secondary mb-4">Create Profile</a>
        <?php else: ?>
            <h3><?php echo forum_username($profile->username) ?></h3>
            <a href="<?php echo base_url('forum/profile') ?>" class="btn btn-sm btn-secondary mb-4">Edit Profile</a>
            <div class="list-group list-group-transparent mb-0 text-left">
                <a href="<?php echo base_url("forum/me/threads") ?>" class="list-group-item list-group-item-action"><span class="icon mr-3"><i class="fe fe-file-text"></i></span>My Started Threads</a>
                <a href="<?php echo base_url("forum/me/replies") ?>" class="list-group-item list-group-item-action"><span class="icon mr-3"><i class="fe fe-corner-down-right"></i></span>My Replies</a>
            </div>
        <?php endif ?>
    </div>
</div>