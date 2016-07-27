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
                            <div class="item"><span class="num">1</span>MySQL Connection Details</div>
                        </li>
                        <li>
                            <div class="item"><span class="num">2</span>List of Databases</div>
                        </li>
                        <li>
                            <div class="item"><span class="num">2</span>Completed backup</div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="steps-numeric-inner connection-detail">
                <header class="steps-numeric-title">Connection Details</header>
                {!! Form::open(['id' => 'form-connection2', 'data-style' => 'sky', 'data-nav' => 'left', 'class' => 'wizard', 'url' => 'mysql']) !!}

                <div class="row">
                    <div class="col-lg-12" style="margin: 5px 0 15px;">
                        <div class="col-lg-3">
                            <div class="radio">
                                <input type="radio" name="type" id="radio-1" value="mysql">
                                <label for="radio-1">MySQL </label>

                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="radio">
                                <input type="radio" name="type" id="radio-2" value="ssh" checked>
                                <label for="radio-2">SSH</label>

                            </div>
                        </div>

                    </div>
                    <div class="col-lg-12">
                        <div class="col-lg-3">
                            <fieldset class="form-group">
                                <label class="form-label" for="exampleInput">MySQL Host Address</label>
                                <input name="host" type="text" class="form-control"
                                       id="exampleInputEmail1"
                                       name="exampleInputEmail1" placeholder="Enter Mysql Host Address" value="localhost">
                            </fieldset>
                        </div>
                        <div class="col-lg-3">
                            <fieldset class="form-group">
                                <label class="form-label" for="exampleInputEmail1">MySQL Username</label>
                                <input name="username" type="text" class="form-control"
                                       id="exampleInputPassword1"
                                       name="exampleInputPassword1" placeholder="Enter MySQL Username" value="sandbox_test">
                            </fieldset>
                        </div>
                        <div class="col-lg-3">
                            <fieldset class="form-group">
                                <label class="form-label" for="exampleInputPassword1">MySQL Password</label>
                                <input id="hide-show-password" type="password" name="password"
                                       placeholder="Enter MySQL Password" class="form-control" value="ZsuW{]Q;oCP9">
                            </fieldset>
                        </div>
                        <div class="col-lg-3">
                            <fieldset class="form-group">
                                <label class="form-label" for="exampleInputEmail1">MySQL Port</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter MySQL Port" name="port" value="3306">
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-lg-12 ssh-part">

                        <div class="col-lg-3">
                            <fieldset class="form-group">
                                <label class="form-label" for="exampleInput">SSH Host Address</label>
                                <input name="sshhost" type="text" class="form-control"
                                       id="exampleInputEmail1"
                                       name="exampleInputEmail1" placeholder="Enter SSH Host Address" value="host.vrazer.com">
                            </fieldset>
                        </div>
                        <div class="col-lg-3">
                            <fieldset class="form-group">
                                <label class="form-label" for="exampleInputEmail1">SSH Username</label>
                                <input name="sshusername" type="text" class="form-control"
                                       id="exampleInputPassword1"
                                       name="exampleInputPassword1" placeholder="Enter SSH Username" value="sandbox">
                            </fieldset>
                        </div>
                        <div class="col-lg-3">
                            <fieldset class="form-group">
                                <label class="form-label" for="exampleInputPassword1">SSH Password</label>
                                <input id="hide-show-password" type="password" name="sshpassword"
                                       placeholder="Enter SSH Password" class="form-control" value=",K2SPs8~vBC-$kiO">
                            </fieldset>
                        </div>
                        <div class="col-lg-3">
                            <fieldset class="form-group">
                                <label class="form-label" for="exampleInputEmail1">SSH Port</label>
                                <input type="text" class="form-control" id="exampleInputEmail11" placeholder="Enter SSH Port" name="sshport" value="22">
                            </fieldset>
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>

            </div>
            <div class="steps-numeric-inner selection-folders">
                <header class="steps-numeric-title">List of Databases</header>
                <div class="col-lg-12">
                    <fieldset>
                        <div id="tree1" class="jstree jstree-1 jstree-default jstree-default-responsive"
                             role="tree" aria-activedescendant="j1_1">
                        </div>
                        <div class="clearfix"></div>
                    </fieldset>
                </div>
            </div>
            <div class="steps-numeric-inner completed-backups">
                <h1 class="backup-heading">Your database backup is completed successfully</h1>
            </div>


            <div class="tbl steps-numeric-footer">
                <div class="tbl-row">
                    <a href="javascript:void(0)" class="tbl-cell return-btn">← Return to Connection Details</a>
                    <a  href="javascript:void(0)" id="btn-connection2" class="tbl-cell color-green cont-btn swal-btn-success">Click to Connect →</a>
                    <a  href="javascript:void(0)" id="btn-connection3" class="tbl-cell color-green cont-btn swal-btn-success2">Create Backup →</a>
                </div>
            </div>

        </section><!--.steps-numeric-block-->

    </div>
</div>