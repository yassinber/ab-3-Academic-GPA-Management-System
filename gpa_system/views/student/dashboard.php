<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-speedometer2 me-2 text-primary"></i>My Current Semester
    </h2>
    <button class="btn btn-outline-secondary btn-sm" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise"></i> Refresh
    </button>
</div>

<div id="studentContent">
    <div class="text-center py-4">
        <div class="spinner-border text-primary mb-2"></div>
        <p class="text-muted">Loading your grades...</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    $.get('api/gpa.php', {action:'current'})
    .done(function(d){
        var h='';
        if(d.error){
            h='<div class="card text-center py-5"><div class="card-body"><i class="bi bi-exclamation-triangle text-warning display-3"></i><h4 class="mt-3">'+esc(d.error)+'</h4></div></div>';
        } else if(!d.courses || d.courses.length===0){
            h='<div class="card text-center py-5"><div class="card-body"><i class="bi bi-journal-x text-info display-3"></i><h4 class="mt-3">No courses available</h4></div></div>';
        } else {
            h='<div class="card shadow-sm mb-4">';
            h+='<div class="card-header bg-primary bg-opacity-10 fw-bold text-primary"><i class="bi bi-calendar3 me-2"></i>'+esc(d.semester.label)+' - '+esc(d.semester.academic_year)+'</div>';
            h+='<div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead class="table-light"><tr><th>Course</th><th class="text-center">Credits</th><th class="text-center">Grade</th><th class="text-center">Points</th></tr></thead><tbody>';
            $.each(d.courses, function(i,c){
                var gb = c.grade!==null ? '<span class="badge '+(c.grade>=3?'bg-success':c.grade>=2?'bg-warning text-dark':'bg-danger')+' fs-6">'+c.grade.toFixed(1)+'</span>' : '<span class="text-muted fst-italic">Pending</span>';
                h+='<tr><td>'+esc(c.course_name)+'</td><td class="text-center">'+c.credits+'</td><td class="text-center">'+gb+'</td><td class="text-center">'+(c.grade!==null?c.grade_points.toFixed(1):'-')+'</td></tr>';
            });
            h+='</tbody></table></div>';
            var gc = d.gpa!==null ? (d.gpa>=3.7?'alert-success':d.gpa>=3?'alert-info':d.gpa>=2?'alert-warning':'alert-danger') : 'alert-secondary';
            h+='<div class="d-flex justify-content-end mt-3"><div class="alert '+gc+' mb-0 px-4 py-3 d-flex align-items-center rounded-3"><i class="bi '+(d.gpa!==null?'bi-trophy-fill':'bi-hourglass-split')+' fs-3 me-3"></i><div><div class="fw-bold">Semester GPA</div><div class="fs-2 fw-bold">'+(d.gpa!==null?d.gpa.toFixed(2):'--')+'</div></div></div></div>';
            h+='</div></div>';
        }
        $('#studentContent').html(h);
    })
    .fail(function(x){
        $('#studentContent').html('<div class="card text-center py-5"><div class="card-body"><i class="bi bi-x-circle text-danger display-3"></i><h4 class="mt-3">Connection error</h4></div></div>');
    });

    function esc(t){ return String(t).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>