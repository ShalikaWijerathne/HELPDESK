<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <form method="GET" action="{{ route($route) }}" class="form-inline">
                    <div class="form-group mr-2 mb-0">
                        <label class="mr-1">From</label>
                        <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
                    </div>
                    <div class="form-group mr-2 mb-0">
                        <label class="mr-1">To</label>
                        <input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">
                        <i class="fa fa-search"></i> Run Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
