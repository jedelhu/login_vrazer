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
                            <div class="item"><span class="num">1</span>Connection Details</div>
                        </li>
                        <li>
                            <div class="item"><span class="num">2</span>Select root folder.</div>
                        </li><li>
                            <div class="item"><span class="num">3</span>Select files and folder.</div>
                        </li>
                        <li>
                            <div class="item"><span class="num">4</span>Completed backup.</div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="steps-numeric-inner connection-detail">
                <header class="steps-numeric-title">Connection Details</header>
                {!! Form::open(['id' => 'form-connection', 'data-style' => 'sky', 'data-nav' => 'left', 'class' => 'wizard', 'url' => 'connection']) !!}

                <div class="row">
                    <div class="col-lg-3">
                        <div class="radio">
                            <input type="radio" name="type" id="radio-1" value="ftp">
                            <label for="radio-1">FTP </label>

                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="radio">
                            <input type="radio" name="type" id="radio-2" value="ftps">
                            <label for="radio-2">FTPS</label>

                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="radio">
                            <input type="radio" name="type" id="radio-3" value="sftp" checked>
                            <label for="radio-3">SFTP</label>

                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="radio">
                            <input type="radio" name="type" id="radio-4" value="ssh">
                            <label for="radio-4">SSH</label>

                        </div>
                    </div>

                    <div class="col-lg-4">
                        <fieldset class="form-group">
                            <label class="form-label semibold" for="exampleInput">Host Name</label>
                            <input name="host" type="text" class="form-control"
                                   id="exampleInputEmail1"
                                   name="exampleInputEmail1" placeholder="Enter Host Name" value="host.vrazer.com" >
                        </fieldset>
                    </div>
                    <div class="col-lg-4">
                        <fieldset class="form-group">
                            <label class="form-label" for="exampleInputEmail1">Username</label>
                            <input name="username" type="text" class="form-control"
                                   id="exampleInputPassword1"
                                   name="exampleInputPassword1" placeholder="Enter Username" value="vrazern">
                        </fieldset>
                    </div>
                    <div class="col-lg-4">
                        <fieldset class="form-group">
                            <label class="form-label" for="exampleInputPassword1">Password</label>
                            <input id="hide-show-password" type="password" name="password" value="IH9mhGP6CoOU"
                                   placeholder="Enter Password" class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-lg-4">
                        <fieldset class="form-group">
                            <label class="form-label" for="exampleInputEmail1">Ports</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Port" name="port" value="2222">
                        </fieldset>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label" for="exampleInputEmail1">Simultaneous Connections</label>
                        <select class="bootstrap-select bootstrap-select-arrow" name="favoriteNumber">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>


                    <input id="dir" type="hidden" name="dir" value="">
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="steps-numeric-inner selection-folders">
                <section class="box-typical box-typical-max-280">
                    <header class="box-typical-header">
                        <div class="tbl-row">
                            <div class="tbl-cell tbl-cell-title">
                                <h3>Select root folder :</h3>
                            </div>
                        </div>
                    </header>
                    <div class="box-typical-body">
                        <div class="table-responsive">

                            <div class="col-lg-12">
                                <fieldset>
                                    <div id="tree1" class="jstree jstree-1 jstree-default jstree-default-responsive"
                                         role="tree" aria-activedescendant="j1_1">
                                    </div>
                                    <div class="clearfix"></div>
                                </fieldset>
                            </div>

                        </div>
                    </div><!--.box-typical-body-->
                </section><!--.box-typical-->
            </div>

            <div class="steps-numeric-inner completed-backups">
                <h1 class="backup-heading">Your backup is completed successfully</h1>
            </div>

            <div class="tbl steps-numeric-footer">
                <div class="tbl-row">
                    <a href="javascript:void(0)" class="tbl-cell return-btn">← Return to Connection Details.</a>
                    <a href="javascript:void(0)" class="tbl-cell return-btn2">← Return to select root folder.</a>
                    <a  href="javascript:void(0)" id="btn-connection" class="tbl-cell color-green cont-btn swal-btn-success">Click to Connect →</a>
                    <a  href="javascript:void(0)" id="box-connection" class="tbl-cell color-green cont-btn">Click to select files and folders →</a>
                    <a  href="javascript:void(0)" id="box-connection2" class="tbl-cell color-green cont-btn swal-btn-success2">Create Backup →</a>
                </div>
            </div>

        </section><!--.steps-numeric-block-->

    </div>
</div>