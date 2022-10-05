<?php if( !empty( $pagination ) && $pagination->pages > 1 ): ?>
    <ul class="pagination">
        <?php if( $pagination->current > 1 ): ?>
            <a class="page-link" href="<?php echo '?page=' . ( $pagination->current - 1 ) ?>">Previous</a>
        <?php else: ?>
            <li class="page-item disabled"><a class="page-link" href="javascript:void(0)">Previous</a></li>
        <?php endif ?>
        <?php if( $pagination->pages > 8 ): ?>
            <?php if( $pagination->current < 5 ): ?>
                <?php for( $i = 1; $i <= 5; $i++ ): ?>
                    <li class="page-item <?php if( $i == $pagination->current ) echo 'active' ?>"><a class="page-link" href="<?php echo '?page=' . $i ?>"><?php echo $i ?></a></li>
                <?php endfor ?>
                <li class="page-item"><a class="page-link text-muted" href="javascript:void(0)">&hellip;</a></li>
                <li class="page-item"><a class="page-link" href="<?php echo '?page=' . ( $pagination->pages - 1 ) ?>"><?php echo $pagination->pages - 1 ?></a></li>
                <li class="page-item"><a class="page-link" href="<?php echo '?page=' . $pagination->pages ?>"><?php echo $pagination->pages ?></a></li>
            <?php elseif( $pagination->current > $pagination->pages - 5 ): ?>
                <li class="page-item"><a class="page-link" href="<?php echo '?page=1' ?>">1</a></li>
                <li class="page-item"><a class="page-link text-muted" href="javascript:void(0)">&hellip;</a></li>
                <?php for( $i = $pagination->pages - 5; $i <= $pagination->pages; $i++ ): ?>
                    <li class="page-item <?php if( $i == $pagination->current ) echo 'active' ?>"><a class="page-link" href="<?php echo '?page=' . $i ?>"><?php echo $i ?></a></li>
                <?php endfor ?>
            <?php else: ?>
                <li class="page-item"><a class="page-link" href="<?php echo '?page=1' ?>">1</a></li>
                <li class="page-item"><a class="page-link" href="<?php echo '?page=2' ?>">2</a></li>
                <li class="page-item"><a class="page-link text-muted" href="javascript:void(0)">&hellip;</a></li>
                <?php for( $i = $pagination->current - 2; $i <= $pagination->current + 2; $i++ ): ?>
                    <li class="page-item <?php if( $i == $pagination->current ) echo 'active' ?>"><a class="page-link" href="<?php echo '?page=' . $i ?>"><?php echo $i ?></a></li>
                <?php endfor ?>
                <li class="page-item"><a class="page-link text-muted" href="javascript:void(0)">&hellip;</a></li>
                <li class="page-item"><a class="page-link" href="<?php echo '?page=' . $pagination->pages ?>"><?php echo $pagination->pages ?></a></li>
            <?php endif ?>
        <?php else: ?>
            <?php for( $i = 1; $i <= $pagination->pages; $i++ ): ?>
                <li class="page-item <?php if( $i == $pagination->current ) echo 'active' ?>"><a class="page-link" href="<?php echo '?page=' . $i ?>"><?php echo $i ?></a></li>
            <?php endfor ?>
        <?php endif ?>

        <?php if( $pagination->current != $pagination->pages ): ?>
            <li class="page-item"><a class="page-link" href="<?php echo '?page=' . ( $pagination->current + 1 ) ?>">Next</a></li>
        <?php else: ?>
            <li class="page-item disabled"><a class="page-link" href="javascript:void(0)">Next</a></li>
        <?php endif ?>
    </ul>
<?php endif ?>