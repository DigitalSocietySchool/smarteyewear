@extends('app')

@section('content')
<header class="page">
	<div class='container'>
		<div class="page-header">
			<h1>To Do</h1>
		</div>
	</div>
</header>
<div class="container content-container">
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default panel-todo">
				<div class="panel-heading">Termijn 4 <span class="items">30</span></div>

				<div class="panel-body">
					<ul class="todo-areas">
						<li>
							<div class="area-title">
								<span class="check"><i class="fa fa-check-square"></i></span>
								102
								<span class="toggle"><i class="fa fa-angle-down"></i></span>
							</div>
							<div class="area-locations">
								<ul class="locations">
									<li>25g</li>
									<li>25g</li>
									<li>25g</li>
									<li>25g</li>
								</ul>
							</div>
						</li>
						<li class="open">
							<div class="area-title">
								<span class="check"><i class="fa fa-check-square"></i></span>
								108
								<span class="toggle"><i class="fa fa-angle-down fa-flip-vertical"></i></span>
							</div>
							<div class="area-locations">
								<ul class="locations">
									<li>25g</li>
									<li>25g</li>
									<li>25g</li>
									<li>25g</li>
								</ul>
							</div>
						</li>
						<li>
							<div class="area-title">
								<span class="check"><i class="fa fa-check-square"></i></span>
								112A
								<span class="toggle"><i class="fa fa-angle-down"></i></span>
							</div>
							<div class="area-locations">
								<ul class="locations">
									<li>25g</li>
									<li>25g</li>
									<li>25g</li>
									<li>25g</li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="panel panel-default panel-todo">
				<div class="panel-heading">Route <span class="items"><a href="javascript:;" class="toggle-route">toon route</a> | <i class="fa fa-print"></i></span></div>

				<div class="panel-body">
					You are logged in!
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
