@extends('admin/layouts/admin')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="content">
                @if ($errors->any())
                    @foreach($errors->all() as $error)
                        {{$error}}
                    @endforeach
                @endif
                <div id="errors"></div>
            </div>
            <section class="box-typical steps-numeric-block">
                <div class="steps-numeric-header">
                    <div class="steps-numeric-header-in">
                        <ul>
                            <li class="active">
                                <div class="item"><span class="num">1</span>Mysql Connection Details</div>
                            </li>
                            <li>
                                <div class="item"><span class="num">2</span>List Of Database</div>
                            </li>
                            <li>
                                <div class="item"><span class="num">3</span>Third Step</div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="steps-numeric-inner connection-detail">
                    <header class="steps-numeric-title">Connection Details</header>
                    {!! Form::open(['id' => 'form-connection2', 'data-style' => 'sky', 'data-nav' => 'left', 'class' => 'wizard', 'url' => 'mysql']) !!}

                    <div class="row">

                        <div class="col-lg-4">
                            <fieldset class="form-group">
                                <label class="form-label semibold" for="exampleInput">Mysql Host Address</label>
                                <input name="host" type="text" class="form-control"
                                       id="exampleInputEmail1"
                                       name="exampleInputEmail1" placeholder="Enter Mysql Host Address" value="localhost">
                            </fieldset>
                        </div>
                        <div class="col-lg-4">
                            <fieldset class="form-group">
                                <label class="form-label" for="exampleInputEmail1">Username</label>
                                <input name="username" type="text" class="form-control"
                                       id="exampleInputPassword1"
                                       name="exampleInputPassword1" placeholder="Enter Username" value="vrazern_admin">
                            </fieldset>
                        </div>
                        <div class="col-lg-4">
                            <fieldset class="form-group">
                                <label class="form-label" for="exampleInputPassword1">Password</label>
                                <input id="hide-show-password" type="password" name="password"
                                       placeholder="Enter Password" class="form-control" value="ac2amNtDtn4Yyp">
                            </fieldset>
                        </div>


                        {{--<input id="dir" type="hidden" name="dir" value="">--}}
                        {!! Form::close() !!}

                    </div>


                </div>
                <div class="steps-numeric-inner selection-folders">
                    <header class="steps-numeric-title">List Of Database</header>
                    <div class="col-lg-12">
                        <fieldset>
                            <div id="tree1" class="jstree jstree-1 jstree-default jstree-default-responsive"
                                 role="tree" aria-activedescendant="j1_1">
                            </div>
                            <div class="clearfix"></div>
                        </fieldset>
                    </div>
                </div>


                <div class="tbl steps-numeric-footer">
                    <div class="tbl-row">
                        <a href="javascript:void(0)" class="tbl-cell return-btn">← Return to Connection Details</a>
                        <a  href="javascript:void(0)" id="btn-connection2" class="tbl-cell color-green cont-btn swal-btn-success">Click to Connect →</a>
                    </div>
                </div>

            </section><!--.steps-numeric-block-->

        </div>
    </div>
@stop
