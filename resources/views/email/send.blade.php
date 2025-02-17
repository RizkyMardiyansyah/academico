@extends('backpack::blank')

@section('after_styles')
    <style media="screen">
    label.required::after {
            content: ' *';
            color: red;
        }
    </style>
@endsection


@section('header')
    <section class="container-fluid">
        <h1>
            Send Email To Student
        </h1>
    </section>
@endsection

@section('content')


    <div class="row">
        <div class="col-md-12">
            <form action="/email/send" method="POST">
                <input type="hidden" value="{{ $course }}" name="courseid"/>

                {!! csrf_field() !!}

                <div class="card">
                    <div class="card-body row">

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group col-sm-12 required">
                            @php
                                $label = trans('Student');
                                $field = 'student'
                            @endphp

                            <label class="required">{{ $label }}</label>
                            <select class="form-control"  name="{{ $field }}" >
                                    <option value="all" @selected($preselect == "all")>All Students (Send this email to all students)</option>
                                @foreach($students as $student)
                                    @if($preselect == $student->email)
                                        <option selected value="{{$student->email}}">{{ $student->username }} ({{$student->email}})</option>
                                    @else
                                        <option value="{{$student->email}}">{{ $student->username }} ({{$student->email}})</option>
                                    @endif
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group col-sm-12 required">
                            @php
                                $label = trans('Subject');
                                $field = 'subject'
                            @endphp

                            <label class="required">{{ $label }}</label>
                            <input required class="form-control" type="text" name="{{ $field }}" value="{{ old($field) ? old($field) : "" }}">

                        </div>

                        <div class="form-group col-sm-12 required">
                            @php
                                $label = trans('Message');
                                $field = 'message'
                            @endphp

                            <label class="required">{{ $label }}</label>
                            <textarea rows="10" required class="form-control" type="text" name="{{ $field }}">
                                 {{ old($field) ? old($field) : "" }}
                            </textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        <span class="ladda-label"><i class="la la-envelope"></i> {{ trans('Send') }}</span>
                    </button>
                    <a href="/email/" class="btn btn-default"><span class="ladda-label">{{ trans('Cancel') }}</span></a>
                </div>
            </form>
        </div>
    </div>
@endsection


