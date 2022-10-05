<div class="dropdown d-none d-md-flex">
    <?php $messages = get_inbox_unread() ?>
    <a class="nav-link icon" data-toggle="dropdown" aria-expanded="false">
    <i class="fe fe-bell"></i>
    <?php if ($messages): ?><span class="nav-unread"></span><?php endif ?>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; transform: translate3d(39px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
        <?php if ($messages): ?>
            <?php foreach ($messages as $message): ?>
                <a href="<?php echo base_url("inbox/view/{$message->id}") ?>" class="dropdown-item d-flex">
                    <div>
                        <?php echo $message->label ?? "You have received a reply from <b>{$message->first_name}</b>." ?>
                        <div class="small text-muted"><?php echo timespan(strtotime('now'), strtotime($message->created), 1) ?></div>
                    </div>
                </a>
            <?php endforeach ?>
        <?php else: ?>

            <a href="<?php echo base_url('inbox') ?>" class="dropdown-item d-flex">
                <div>You do not have any unread messages. <br><span class="text-cyan">Go to my Inbox</span></div>
            </a>
        <?php endif ?>
        <div class="dropdown-divider"></div>
        <a href="javascript:mark_all_as_read()" class="dropdown-item text-center">Mark all as read</a>
    </div>
</div>