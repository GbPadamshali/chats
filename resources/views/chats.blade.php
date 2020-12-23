@extends('layouts.app')

@section('content')
<style media="screen">
    .new-message {
        margin-top: 10px;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <p class="text-success">
                        <strong>{{ $user->name }}</strong>
                    </p>
                    <input type="hidden" name="sender_id" id="sender_id" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="receiver_id" id="receiver_id" value="{{ $user->id }}">
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{-- {{ __('You are logged in!') }} --}}
                    Welcome to {{ $user->name }}'s chatting Page.
                    <hr>
                    {{-- <div class="unread_messages">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <span class="text-secondary">Unread Messages</span>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="chat-messages">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <span class="text-success">
                                    <strong>{{ $user->name }}</strong>
                                </span>
                                <br>
                                Hello World!!!
                            </div>
                        </div>
                    </div> --}}
                    <div class="new-message">
                        <div class="row">
                            <div class="col-md-11">
                                <textarea name="message" id="message" rows="2" class="form-control" style="resize: none">Type message here...</textarea>
                            </div>
                            <div class="col-md-1 pull-right">
                                <button type="button" name="send" id="send" class="btn btn-lg btn-success"><strong>Send</strong></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var count = 1;
    $(document).ready(function() {
        getMessages();
    });

    setInterval(function() { getMessages(); }, 50000);

    $("body").on("click", "#message", function() {
        if ($("#message").val() == "Type message here...") {
            $("#message").val('');
        }
    })

    setInterval(function() {
        if ($(".unread_messages").length > 0) {
            $.ajax({
                url: "{{ route('chat.read_messages') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    sender_id: $("#sender_id").val(),
                    receiver_id: $("#receiver_id").val(),
                },
                dataType:'JSON',
                success:function(data) {
                    $(".unread_messages").remove();
                }
            })
        }
    }, 5000);

    function getMessages() {
        $.ajax({
            url: "{{ route('chat.get_messages') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                sender_id: $("#sender_id").val(),
                receiver_id: $("#receiver_id").val(),
                count: count
            },
            dataType: "JSON",
            success:function(data) {
                var sender_name = "{{ Auth::user()->name }}";
                var unread_html = `<div class="unread_messages"><div class="row"><div class="col-md-12 text-center"><span class="text-secondary">Unread Messages</span></div></div></div>`;
                var html = '';

                for (var i = 0; i < data.messages.length; i++) {
                    // data.messages[i]
                    if (data.messages[i].sender_id == $("#sender_id").val()) {
                        html += `<div class="chat-messages"><div class="row"><div class="col-md-12 text-right"><span class="text-primary"><strong>`+data.messages[i].receiver_name+`</strong></span><br>`+data.messages[i].message+`</div></div></div>`;
                    } else {
                        html += `<div class="chat-messages"><div class="row"><div class="col-md-12 text-left"><span class="text-success"><strong>`+sender_name+`</strong></span><br>`+data.messages[i].message+`</div></div></div>`;
                    }
                }
                if ($(".unread_messages").length <= 0 && html != "" && count > 1) {
                    $(".new-message").before(unread_html);
                }
                $(".new-message").before(html);
                // console.log(data.messages[0].receiver_name);
                count = count+1;
            }
        });
    }

    $("body").on("click", "#send", function() {
        $.ajax({
            url: "{{ route('chat.send_message') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                sender_id: $("#sender_id").val(),
                receiver_id: $("#receiver_id").val(),
                message: $("#message").val()
            },
            dataType: "JSON",
            success: function(data) {
                getMessages();
                $("#message").val('');
            }
        })
    });
</script>
@endsection
