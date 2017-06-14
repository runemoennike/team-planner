<h1>Manning project <?php echo $this->project->name ?></h1>

<ul>
    <li><a href="/">Home</a></li>
    <li><a href="<?php echo URL_BASE.'projects/list' ?>">Projects</a></li>
</ul>

<p><?php echo $this->project->description ?></p>

<p>Skills required:</p>
<ul>
    <?php foreach($this->project->skills as $skill) : ?>
        <li>
            <?php echo $skill->name ?>
            <?php if($this->project->isSkillCovered($skill->id)) : ?>
                <?php echo str_repeat('&checkmark;', $this->project->countSkillCoverage($skill->id)) ?>
            <?php endif ?>
        </li>
    <?php endforeach ?>
</ul>

<p>
    Current manning score: <b><?php echo $this->project->calculateManningScore() ?>%</b>
</p>

<h2>People</h2>
<form method="post" enctype="multipart/form-data">
    <table>
        <thead>
        <tr>
            <td></td>
            <td></td>
            <td>Name</td>
            <td>Education</td>
            <td>Skills</td>
            <td>Technologies</td>
            <td>Hired year</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach($this->people as $person) : ?>
            <tr>
                <td>
                    <input type="checkbox" name="people[]" value="<?php echo $person->id ?>" <?php if($this->project->hasPerson($person->id)) echo 'checked' ?> />
                </td>
                <td>
                    <img src="uploads/<?php echo $person->id ?>.jpeg" style="width:50px;height:50px;">
                </td>
                <td>
                    <?php echo $person->name ?>
                </td>
                <td>
                    <?php echo $person->education ?>
                </td>
                <td>
                    <?php if(!empty($person->skills)) : ?>
                        <?php foreach($person->skills as $skill) : ?>
                            <?php
                                $required = $this->project->hasSkill($skill->id);
                                $covered = $this->project->isSkillCovered($skill->id);
                            ?>
                            <span style="<?php if($required && !$covered) echo 'color:red;'; elseif($required && $covered) echo 'color:blue' ?>">
                                <?php echo $skill->name ?>
                            </span>
                        <?php endforeach ?>
                    <?php endif ?>
                </td>
                <td>
                    <?php if(!empty($person->technologies)) : ?>
                        <?php foreach($person->technologies as $technology) : ?>
                            <?php echo $technology->name ?>
                        <?php endforeach ?>
                    <?php endif ?>
                </td>
                <td>
                    <?php echo $person->hiredYear ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    <input type="submit" />

</form>
