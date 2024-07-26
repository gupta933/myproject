<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <meta name="api-key" content="helloatg">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
  </head>
  <body>
    <div class="container">
        @if (isset($user))
            <h1>Hello, <span class="text-success">{{ $user->name }}!</span></h1>
            <h2>Welcome to Dashboard</h2>
            <a href="{{ route('logout') }}" class="btn text-danger bg-warning" style="text-align: right">Logout</a>
        @else
            <?php return view('index'); ?>
        @endif
    </div>
    <br><br>
    <div class="container mt-5">
        <form id="addTaskForm">
            <div class="form-group">
                <label for="task">New Task</label>
                <input type="text" class="form-control" id="task" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Task</button>
        </form>
        <h2 class="mt-4">Your Tasks</h2>
        <ul class="list-group mt-4" id="taskList">
            @forelse ($tasks as $task)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $task->task }}</span>
                    <div>
                        <button class="btn btn-success btn-sm mark-done" data-id="{{ $task->id }}">Mark Done</button>
                        <button class="btn btn-warning btn-sm mark-pending" data-id="{{ $task->id }}">Mark Pending</button>
                    </div>
                </li>
            @empty
                <li class="list-group-item">You have no tasks yet.</li>
            @endforelse
        </ul>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#addTaskForm').on('submit', function(e) {
               // e.preventDefault();

                const task = $('#task').val();
                const userId = "{{ auth()->user()->id }}";
                const apiKey = $('meta[name="api-key"]').attr('content');
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '/api/todo/add',
                    method: 'POST',
                    headers: {
                        'API_KEY': apiKey,
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        task: task,
                        user_id: userId
                    },
                    success: function(response) {
                        if (response.status === 1) {
                            $('#taskList').append(`
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>${response.task.task}</span>
                                    <div>
                                        <button class="btn btn-success btn-sm mark-done" data-id="${response.task.id}">Mark Done</button>
                                        <button class="btn btn-warning btn-sm mark-pending" data-id="${response.task.id}">Mark Pending</button>
                                    </div>
                                </li>
                            `);
                            $('#task').val('');
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            $('#taskList').on('click', '.mark-done', function() {
                const taskId = $(this).data('id');
                const apiKey = $('meta[name="api-key"]').attr('content');

                $.ajax({
                    url: '/api/todo/status',
                    method: 'POST',
                    headers: { 'API_KEY': apiKey },
                    data: { task_id: taskId, status: 'done' },
                    success: function(response) {
                        if (response.status === 1) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            $('#taskList').on('click', '.mark-pending', function() {
                const taskId = $(this).data('id');
                const apiKey = $('meta[name="api-key"]').attr('content');

                $.ajax({
                    url: '/api/todo/status',
                    method: 'POST',
                    headers: { 'API_KEY': apiKey },
                    data: { task_id: taskId, status: 'pending' },
                    success: function(response) {
                        if (response.status === 1) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
  </body>
</html>
