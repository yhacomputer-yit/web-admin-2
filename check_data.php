<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Course;
use App\Models\Project;

echo "Courses:\n";
foreach(Course::all() as $c) {
    echo $c->name . ' (id:' . $c->id . ', type:' . $c->type . ")\n";
}

echo "\nProjects:\n";
foreach(Project::with('course')->get() as $p) {
    echo $p->title . ' - ' . $p->course->name . ' (type:' . $p->course->type . ")\n";
}
