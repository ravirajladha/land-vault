@php
    use Carbon\Carbon;
@endphp

<x-app-layout>


    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Document</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Document Logs</a></li>
                    </ol>
                </div>

                {{-- Display success message --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Display validation errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">

                                    <div class="table-responsive">
                                        @if ($documentTransactions->isEmpty())
                                         
                                            <p>No document logs available.</p>
                                        @else
                                        <table class="table table-responsive-sm">

                                            {{-- <button type="button" class="btn btn-success mb-2 float-end"   data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Assign Document</button> --}}
                                 

                                            {{-- {{ dd($documentAssignments) }} --}}
                                            {{-- @if ($user && $user->hasPermission('Add Assigned Docs to Advocate'))
                                                <button class="btn btn-success btn-sm assign-doc-btn float-end flex"
                                                    title="Assign Document to the Advocate" data-bs-toggle="modal"
                                                    data-bs-target="#assignDocumentModal"
                                                    data-receiver-id="{{ $advocateId }}"><i
                                                        class="fas fa-plus-square"></i>&nbsp;Assign Document
                                                </button>
                                            @endif --}}
                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>

                                               
                                                    
                                                     
                                                        <th>Doc Name</th>
                                                        <th>Doc Type</th>
                                                        <th>Transaction Type</th>
                                                        <th>Notes</th>
                                                        <th>Creator</th>
                                                        <th>Created At</th>
                                                        <th>Updated At</th>
                                                  

                                                </tr>
                                            </thead>
                                            <tbody>
                                             {{-- {{ dd($documentTransactions) }} --}}
                                             @foreach ($documentTransactions as $index=> $transaction)
                                             <tr>
                                                 <td>{{ $index+1}}</td>
                                                 <td>   <a href="/review_doc/{{ $transaction->document_type_name}}/{{ $transaction->child_id }}" style="color: #1714c9; text-decoration: underline;">
                                                    {{ $transaction->document_name  }}
                                                </a></td>
                                            
                                                @php
                                                // Split document_type_name by underscore and capitalize each word
                                                $documentTypeName = ucwords(str_replace('_', ' ', $transaction->document_type_name));
                                            
                                                // Set badge class based on transaction_type
                                                $badgeClass = $transaction->transaction_type === 'returned' ? 'badge-success' : 'badge-primary';
                                                $badgeText = $transaction->transaction_type === 'returned' ? 'Returned' : 'Taken';
                                            @endphp
                                            
                                            <td>{{ $documentTypeName }}</td>
                                            <td><span class="badge {{ $badgeClass }}">{{ $badgeText }}</span></td>
                                            
                                                 <td>{{ $transaction->notes ?? 'N/A' }}</td>
                                                 <td>{{ $transaction->creator->name ?? 'N/A' }}</td>
                                                 <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d-M-Y H:i:s') }}</td>

                                                 <td>{{ \Carbon\Carbon::parse($transaction->udpated_at)->format('d-M-Y H:i:s') }}</td>

                                             </tr>
                                         @endforeach
                                            </tbody>
                                        </table>
                                        @if ($documentTransactions->isEmpty())
                                            <p>No document logs available.</p>
                                        @endif
                                        <div class="row">
                                            <div class="col">
                                                {{ $documentTransactions->links('vendor.pagination.custom') }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('layouts.footer')


</x-app-layout>
{{-- add modal  --}}
