<h1><?php echo empty($this->person->id) ? 'Add project' : 'Edit project' ?></h1>

<ul>
    <li><a href="/">Home</a></li>
    <li><a href="<?php echo URL_BASE.'projects/list' ?>">Projects</a></li>
</ul>

<form method="post" enctype="multipart/form-data">
    <div>
        <label for="name">Name</label>
        <input name="name" id="name" value="<?php echo $this->project->name ?>" required />
    </div>

    <div>
        <label for="description">Description</label>
        <textarea name="description" id="description"><?php echo $this->project->description ?></textarea>
    </div>

    <div>
        <label for="skills">Skills</label>
        <select name="skills[]" id="skills" multiple>
            <?php foreach($this->skills as $skill) : ?>
                <option value="<?php echo $skill->id ?>" <?php if($this->project->hasSkill($skill->id)) echo 'selected' ?>><?php echo $skill->name ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <input type="submit" />
</form>
