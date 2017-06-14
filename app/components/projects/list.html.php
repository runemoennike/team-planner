<h1>Projects</h1>

<ul>
    <li><a href="/">Home</a></li>
    <li><a href="<?php echo URL_BASE.'projects/add' ?>">Add new project</a></li>
</ul>

<table>
    <thead>
        <tr>
            <td>Name</td>
            <td>Description</td>
            <td>Manning score</td>
            <td>Skills</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach($this->projects as $project) : ?>
            <tr>
                <td>
                    <?php echo $project->name ?>
                </td>
                <td>
                    <?php echo $project->description ?>
                </td>
                <td>
                    <?php echo $project->calculateManningScore() ?>%
                </td>
                <td>
                    <?php if(!empty($project->skills)) : ?>
                        <?php foreach($project->skills as $skill) : ?>
                            <?php echo $skill->name ?>
                        <?php endforeach ?>
                </td>
                <?php endif ?>
                <td>
                    <a href="<?php echo URL_BASE.'projects/edit'.URL_PARAM_START.'projectId='.$project->id ?>">Edit</a>
                    <a href="<?php echo URL_BASE.'projects/man'.URL_PARAM_START.'projectId='.$project->id ?>">Man</a>
                    <a href="<?php echo URL_BASE.'projects/report'.URL_PARAM_START.'projectId='.$project->id ?>">Report</a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
