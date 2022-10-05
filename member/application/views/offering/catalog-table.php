<?php $this->load->view('header-sya') ?>
<div class="page offering">
    <div class="page-main">
        <div class="heading">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1><?php echo get_title() ?></h1>
                    </div>
                    <div class="col-md-8">
                        <p>Our community offers hundreds of STEM programs, events, and resources. Search our comprehensive catalog for current and upcoming offerings.</p>
                        <?php if ($this->router->fetch_method() != 'organization'): ?>
                            <p>Take a quick quiz to find out what kind of offerings may interest you.</p>
                            <a href="<?php echo base_url("offering/quiz_start") ?>" class="btn mt-3 mr-3">Take Quiz</a>

                            <a class="btn btn-secondary mt-3 " href="<?php echo base_url('offering/internship') ?>">Internships <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="offerings">
            <div class="search-wrap">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <form class="input-icon">
                                <input type="search" class="form-control header-search data-table-search" placeholder="Search…" tabindex="1">
                                <div class="input-icon-addon">
                                    <i class="fe fe-search"></i>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container bg-white pt-3">
                <div class="row filters">
                    <div class="col-lg-12">
                        <a class="btn btn-secondary btn-sm" data-toggle="collapse" href="#filter-collapse" role="button" aria-expanded="false" aria-controls="filter-collapse">
                            Filters <?php echo empty($params) ? null : '<span class="badge badge-light">' . count($params) . '</span>'  ?> <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                        <div class="collapse card bg-light p-4" id="filter-collapse">
                            <form>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <?php foreach ($params as $category => $selections): ?>
                                            <a href="?<?php echo filter_url($category, null, $params) ?>">
                                                <div class="filter-active">
                                                    <?php echo filter_category($category) ?>:
                                                    <span class="text-light  font-weight-normal">
                                                        <?php if (is_array($selections)): ?>
                                                            <?php foreach ($selections as $key => $label): ?>
                                                                <?php if ($key > 0): echo ", "; endif; ?>
                                                                <?php echo filter_label($label) ?>
                                                                <input type="hidden" name="<?php echo $category ?>[]" value="<?php echo $label ?>">
                                                            <?php endforeach ?>
                                                        <?php else: ?>
                                                            <?php echo filter_label($selections) ?>
                                                            <input type="hidden" name="<?php echo $category ?>[]" value="<?php echo $selections ?>">
                                                        <?php endif ?>
                                                    </span>
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </div>
                                            </a>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-muted">
                                        <p>Select one or more filters, then click <b>Apply Filters</b> below.</p>
                                    </div>
                                    <?php foreach($active_filters as $active_filter): ?>
                                        <?php 
                                        $filter_category = $active_filter->filter;
                                        $filter = $filters[$active_filter->filter] ?? null;
                                        ?>
                                        <?php if (!empty($filter)): ?>
                                            <?php if (in_array('category', array_keys($params)) && $filter_category == 'subcategory'): ?>
                                                <!-- Filter for Subcategory, only when Category is selected -->
                                                <div class="col-lg-3">
                                                    <b class="filter-header"><?php echo filter_category($filter_category) ?></b>
                                                    <ul class="filter-list">
                                                        <?php foreach ($filter as $label => $count): ?>
                                                            <?php if ($count > 0): ?>
                                                                <li>
                                                                    <label>
                                                                        <input type="checkbox" name="<?php echo $filter_category ?>[]" value="<?php echo $label ?>">
                                                                        <?php echo filter_label($label) ?>
                                                                        <span class="text-muted">(<?php echo $count ?>)</span>
                                                                    </label>
                                                                </li>
                                                            <?php endif ?>
                                                        <?php endforeach ?>
                                                    </ul>
                                                </div>
                                            <?php endif ?>
                                            <?php if (!in_array($filter_category, array_keys($params)) && $filter_category != 'subcategory'): ?>
                                                <?php if ($filter_category == 'organization'): ?>
                                                    <div class="col-lg-6">
                                                        <b class="filter-header">Organization</b>
                                                        <ul class="filter-list">
                                                            <?php $organization_index = 0 ?>
                                                            <?php foreach ($filter as $label => $count): ?>
                                                                <li>
                                                                    <label>
                                                                        <input type="checkbox" name="<?php echo $filter_category ?>[]" value="<?php echo $label ?>">
                                                                        <?php echo filter_label($label) ?>
                                                                        <span class="text-muted">(<?php echo $count ?>)</span>
                                                                    </label>
                                                                </li>
                                                                <?php unset($filter[$label]) ?>
                                                                <?php $organization_index++ ?>
                                                                <?php if ($organization_index > 3) break; ?>
                                                            <?php endforeach ?>
                                                            <div class="collapse" id="organization_collapse">
                                                                <?php foreach ($filter as $label => $count): ?>
                                                                    <li>
                                                                        <label>
                                                                            <input type="checkbox" name="<?php echo $filter_category ?>[]" value="<?php echo $label ?>">
                                                                            <?php echo filter_label($label) ?>
                                                                            <span class="text-muted">(<?php echo $count ?>)</span>
                                                                        </label>
                                                                    </li>
                                                                <?php endforeach ?>
                                                            </div>
                                                            <li><a data-toggle="collapse" href="#organization_collapse" role="button" aria-expanded="false" aria-controls="organization_collapse">View All ...</a></li>
                                                        </ul>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="col-lg-3">
                                                        <b class="filter-header"><?php echo filter_category($filter_category) ?></b>
                                                        <ul class="filter-list">
                                                            <?php foreach ($filter as $label => $count): ?>
                                                                <?php if ($count > 0): ?>
                                                                    <li>
                                                                        <label>
                                                                            <input type="checkbox" name="<?php echo $filter_category ?>[]" value="<?php echo $label ?>">
                                                                            <?php echo filter_label($label) ?>
                                                                            <span class="text-muted">(<?php echo $count ?>)</span>
                                                                        </label>
                                                                    </li>
                                                                <?php endif ?>
                                                            <?php endforeach ?>
                                                        </ul>
                                                    </div>
                                                <?php endif ?>
                                            <?php endif ?>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                    <div class="col-12">
                                        <input type="submit" value="Apply Filters" class="btn btn-secondary btn-sm mt-3">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table card-table data-table data-table-offerings table-hover">
                            <thead class="text-muted">
                                <tr>
                                    <?php foreach($columns as $column): ?>
                                        <td><?php echo get_offering_table_header($column->label) ?></td>
                                    <?php endforeach ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($offerings as $offering): ?>
                                    <tr>
                                        <?php foreach ($columns as $index => $column) {

                                            if ($column->name == 'start_date'){
                                                $date_column = $index;
                                            }
                                            echo "<td class='column-{$column->name}'>";
                                            if ($column->name == 'title') {
                                                echo '<a href="' . base_url("offering/view/{$offering->id}") . '">';
                                                if ($offering->ose_grade == 1 || $offering->ose_grade == 1) {
                                                    echo '<img class="ose-approved-sm" src="' . base_url('img/offering/approved.png') . '" data-toggle="tooltip" data-placement="bottom" title="Omaha STEM Ecosystem Approved Program">';
                                                }
                                                echo $offering->title;
                                                echo '<div class="text-muted">' . character_limiter($offering->description, 80) . '</div>';
                                                echo '<div class="d-md-none mt-2 text-muted badge bg-light">' . date('m/d/Y', strtotime($offering->next_date)) . '</div>' . '</a>';
                                            }
                                            if ($column->name == 'format') {
                                                echo ucwords($offering->format);
                                                echo '<div class="text-muted">' . ($offering->location ? $offering->location : "Virtual") . '</div>';
                                            }
                                            if ($column->name == 'category') {
                                                echo ucwords($offering->category);
                                                echo '<div class="text-muted">' . $offering->subcategory . '</div>';
                                            }
                                            if ($column->name == 'audience_type') {
                                                echo ucwords($offering->audience_type, '/');
                                            }
                                            if ($column->name == 'organization') {
                                                echo ucwords($offering->organization);
                                                if ($offering->department) {
                                                    echo '<div class="text-muted">' . $offering->department . '</div>';
                                                }
                                            }
                                            if ($column->name == 'start_date') {
                                                echo date('m/d/Y', strtotime($offering->next_date));
                                                if ($offering->end_date) {
                                                    echo '<div class="text-muted">Ends ' . date('m/d/Y', strtotime($offering->end_date)) . '</div>';
                                                }

                                            }
                                            if ($column->name == 'start_time') {
                                                if (!empty($offering->start_time) && $offering->start_time != '00:00:00') {
                                                    echo date('g:iA', strtotime($offering->start_time));
                                                } else {
                                                    echo '-';
                                                }
                                                if (!empty($offering->end_time) && $offering->end_time != '00:00:00') {
                                                    echo '<div class="text-muted">Ends ' . date('g:iA', strtotime($offering->end_time)) . '</div>';
                                                }
                                            }
                                            if ($column->name == 'repeat') {
                                                echo $offering->repeat ? "Yes" : "No";
                                            }
                                            if ($column->name == 'fee') {
                                                echo "<div class='d-none'>" . sprintf('%010.2f', (float)$offering->fee_price) . "</div>";
                                                echo $offering->fee ? "$" . number_format($offering->fee_price, 2) : "Free";
                                            }
                                            if ($column->name == 'group_size') {
                                                if (!empty($offering->group_size)) {
                                                    echo implode(", ", $offering->group_size) . " people";
                                                }
                                            }
                                            if ($column->name == 'fee_has_scholarship') {
                                                echo $offering->fee_has_scholarship ? "Yes" : "No";
                                            }
                                            if ($column->name == 'audience_age_group') {
                                                if (!empty($offering->audience_age_group)) {
                                                    echo implode(", ", $offering->audience_age_group);
                                                }
                                            }
                                            if ($column->name == 'audience_is_supervision_required') {
                                                echo $offering->audience_is_supervision_required ? "Yes" : "No";
                                            }
                                            if ($column->name == 'audience_educator_target') {
                                                if (!empty($offering->audience_educator_target)) {
                                                    echo ucwords(implode(", ", $offering->audience_educator_target));
                                                }
                                            }
                                            if ($column->name == 'is_ada') {
                                                echo $offering->is_ada ? "Yes" : "No";
                                            }
                                            echo "</td>";
                                        } ?>

                                    </tr>
                                <?php endforeach ?>
                                <?php if (empty($offerings)): ?>
                                    <tr>
                                        <td class="text-muted" colspan="<?php echo count($columns) ?>">Sorry, there are no offerings with the current filters. Click on the "Filters" button above to remove some filters.</td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                        <div class="data-table-pagination float-left <?php echo count($offerings) <= 15 ? 'd-none' : '' ?>"></div>
                        <div class="data-table-records-info d-none"></div>
                        <div class="data-table-show-per-page float-right <?php echo count($offerings) <= 15 ? 'd-none' : '' ?>"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 offset-lg-2 text-muted text-center my-5">
                        Want to tell us about an offering? <a href="<?php echo base_url('offering/submit') ?>" class="btn btn-outline-primary btn-sm ml-1">Submit one here!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var date_column_index = <?php echo $date_column ?? 0 ?>
</script>
<?php 

$js = array(
    base_url('js/datatables.js'),
    base_url('js/datatables-bootstrap.js'),
    base_url('js/member.js'),
);
?>
<?php $this->load->view('footer-aim', compact('js')) ?>