<h1>Manning report for project <?php echo $this->project->name ?></h1>

<ul>
    <li><a href="/">Home</a></li>
    <li><a href="<?php echo URL_BASE.'projects/list' ?>">Projects</a></li>
</ul>

<p><?php echo $this->project->description ?></p>

<p>Skills required:
    <?php foreach($this->project->skills as $skill) : ?>
        <?php echo $skill->name ?>
    <?php endforeach ?>
</p>

<p>
    Manning score: <b><?php echo $this->project->calculateManningScore() ?>%</b>
</p>

<h2>Team</h2>
<?php foreach($this->project->people as $person) : ?>
    <h3><?php echo $person->name ?></h3>
    <table>
        <tr>
            <td>
                <img src="uploads/<?php echo $person->id ?>.jpeg" style="width:200px;height:auto;">
            </td>
            <td>
                <ul>
                    <li>Phone: <?php echo $person->phone ?></li>
                    <li>Education: <?php echo $person->education ?></li>
                    <li>Email: <?php echo $person->email ?></li>
                    <?php if(!empty($person->skills)) : ?>
                        <li>
                            Skills:
                            <?php foreach($person->skills as $skill) : ?>
                                <?php echo $skill->name ?>
                            <?php endforeach ?>
                        </li>
                    <?php endif ?>
                    <?php if(!empty($person->technologies)) : ?>
                        <li>
                            Technologies:
                            <?php foreach($person->technologies as $technology) : ?>
                                <?php echo $technology->name ?>
                            <?php endforeach ?>
                        </li>
                    <?php endif ?>
                    <li>Hired year: <?php echo $person->hiredYear ?></li>
                </ul>
            </td>
        </tr>
    </table>
<?php endforeach ?>
