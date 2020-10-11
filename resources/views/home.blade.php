@extends('layouts.app')

@section('content')

    <div id="loader"></div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Todo item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="todo_form">
                        <label for="title">Title</label>
                        <br>
                        <input type="text" class="form-control" name="title" id="title">
                        <i id="titleerror" style="color:red;"></i>
                        <br>
                        <label for="description">Description</label>
                        <br>
                        <input type="text" class="form-control" name="description" id="description">
                        <i id="descerror" style="color:red;"></i>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="mclose" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 border" id="articles">
                <hr>
                <h4>Remaining</h4>
                <hr>
                NO DATA AVAILABLE
            </div>
            <div class="col-md-4 border" id="working">
                <hr>
                <h4>Working</h4>
                <hr>

            </div>
            <div class="col-md-4 border" id="done">
                <hr>
                <h4>Done</h4>
                <hr>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

        /*
        *
        *   Hide Loader
        *
        */
        function hideloader()
        {
            setTimeout(document.getElementById("loader").style.display = "none", 3000);
        }

        /*
        *
        *   Loades Articles Into Articles,working,done section
        *
        */

        function loadarticles() {

            $(".loader").show();
            $.ajax({
                type: 'GET',
                url: '/gettodo',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(res) {
                    $("#articles").html(res);
                },
                error: function(res) {
                    // alert("fail");
                }
            });

            $.ajax({
                type: 'GET',
                url: '/gettododone',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(res) {
                    $("#done").html(res);
                },
                error: function(res) {
                    // alert("fail");
                }
            });

            $.ajax({
                type: 'GET',
                url: '/gettodoworking',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(res) {
                    $("#working").html(res);
                },
                error: function(res) {
                    // alert("fail");
                }
            });

            $(".loader").hide();
        }

        function deletetodo(id) {
            $.ajax({
                type: 'GET',
                url: '/deletetodo',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(res) {
                    // alert("suc");
                    loadarticles();
                },
                error: function(res) {
                    // alert("fail");
                    loadarticles();
                }
            });
        }

        $(document).ready(function() {

            $("#articles").sortable({
                connectWith: "#done,#working",
                stop: function(event, ui) {
                    var id = ui.item.attr('id');
                    var rec = ui.item.parent().attr('id');

                    $.ajax({
                        type: 'POST',
                        url: '/updatetodo',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            belongsto: rec,
                        },
                        success: function(res) {
                            // alert("suc");
                            loadarticles();
                        },
                        error: function(res) {
                            // alert("fail");
                            loadarticles();
                        }
                    })
                }
            });
            $("#done").sortable({
                connectWith: "#articles,#working",
                stop: function(event, ui) {
                    var id = ui.item.attr('id');
                    var rec = ui.item.parent().attr('id');

                    $.ajax({
                        type: 'POST',
                        url: '/updatetodo',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            belongsto: rec,
                        },
                        success: function(res) {
                            // alert("suc");
                            loadarticles();
                        },
                        error: function(res) {
                            // alert("fail");
                            loadarticles();
                        }
                    })
                }
            });
            $("#working").sortable({
                connectWith: "#articles,#done",
                stop: function(event, ui) {
                    var id = ui.item.attr('id');
                    var rec = ui.item.parent().attr('id');

                    $.ajax({
                        type: 'POST',
                        url: '/updatetodo',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            belongsto: rec,
                        },
                        success: function(res) {
                            // alert("suc");
                            loadarticles();
                        },
                        error: function(res) {
                            // alert("fail");
                            loadarticles();
                        }
                    })
                }
            });

            loadarticles();

            $("#todo_form").submit(function(e) {
                e.preventDefault();
                $title = $("#title").val();
                $description = $("#description").val();
                $belongsto='articles';
                $user_id='<?php echo Auth::user()->id ?>';
                $.ajax({
                    type: 'POST',
                    url: '/addtodo',
                    data: {
                        _token: "{{ csrf_token() }}",
                        title: $title,
                        description: $description,
                        belongsto:$belongsto,
                        user_id:$user_id,
                    },
                    success: function(res) {
                        $('#mclose').click();
                        $("#title").val("");
                        $("#description").val("");
                        $("#titleerror").val("");
                        $("#descerror").val("");
                        loadarticles();
                    },
                    error: function(res) {

                        if (!res.responseJSON.success) {
                            $("#titleerror").append(res.responseJSON.errors.title);
                            $("#descerror").append(res.responseJSON.errors.description);
                        }
                        $('#mclose').click();
                        loadarticles();
                    }
                });
            });
        });

    </script>
@endsection
