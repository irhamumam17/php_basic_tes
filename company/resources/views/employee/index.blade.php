@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Employee Data</h3>
                            <button type="button" @click="create" class="btn btn-primary btn-rounded btn-sm mr-1 mb-1"
                                data-toggle="modal"><i class="fas fa-add"></i> Add
                                Data</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" style="width: 100%;">
                                <tr>
                                    <th>No. </th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Company</th>
                                    <th>Action</th>
                                </tr>
                                @foreach ($employees as $key => $employee)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>{{ $employee->company->name }}</td>
                                        <td>
                                            <a href="javascript:void(0)" v-on:click="edit('{{ $employee->id }}')" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="javascript:void(0)" v-on:click="destroy('{{ $employee->id }}')" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="text-left">
                            Display {{ $employees->perPage() }} of {{ $employees->total() }} Data
                        </div>
                        {{-- <div class="text-left">
                            Page {{ $employees->currentPage() }} of {{ $employees->lastPage() }}
                        </div> --}}
                        <div class="text-left">{{ $employees->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@{{ editMode ? 'Edit' : 'Create' }} Data</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        aria-hidden="true">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? update() : store()">
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" v-model="form.name" placeholder="Input Name" class="form-control" required>
                            <div class="text-sm text-danger" v-show="errors.name.length > 0">
                                @{{ errors . name[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <input type="email" v-model="form.email" placeholder="Input Email" class="form-control" required>
                            <div class="text-sm text-danger" v-show="errors.email.length > 0">
                                @{{ errors . email[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <select name="company_id" id="company_id" v-model="form.company_id" class="form-control" required>
                                <option value="" selected hidden>Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        let app = new Vue({
            el: '#app',
            data: {
                search: '',
                createMode: false,
                editMode: false,
                form: {
                    id: '',
                    name: '',
                    email: '',
                    company_id: ''
                },
                errors: {
                    name: [],
                    email: [],
                    company_id: []
                },
            },
            mounted() {
                $('#company-link').addClass('active');
            },
            methods: {
                setMode(create, edit) {
                    this.createMode = create;
                    this.editMode = edit;
                },
                clearForm() {
                    for (let key in this.form) {
                        this.form[key] = '';
                    }
                },
                fillForm(data) {
                    for(let key in this.form) {
                        this.form[key] = data[key];
                    }
                },
                buildForm() {
                    let formData = new FormData();
                    for(let key in this.form) {
                        formData.append(key, this.form[key]);
                    }
                    return formData;
                },
                create() {
                    this.setMode(true, false);
                    this.clearForm();
                    $('#myModal').modal('show');
                },
                store() {
                    Swal.fire({
                        title: 'Please Wait...',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    axios({
                        method: 'POST',
                        url: "{{ route('employee.store') }}",
                        data: this.buildForm(),
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        if (response.data.meta.code == 200) {
                            Swal.fire(
                                'Success',
                                response.data.meta.message,
                                'success'
                            ).then(() => {
                                Swal.close();
                                window.location.reload();
                            });
                        }
                    }).catch(error => {
                        if (error.response.status == 422) {
                            Swal.close();
                            for (let key in error.response.data.errors) {
                                this.errors[key] = error.response.data.errors[key];
                            }
                        } else {
                            Swal.fire(
                                'Error',
                                error.response.data.message,
                                'error'
                            );
                        }
                    });
                },
                edit(id) {
                    this.setMode(false, true);
                    this.clearForm();
                    axios({
                        method: 'GET',
                        url: "{{ route('employee.edit', ':id') }}".replace(':id', id),
                    }).then(response => {
                        if (response.data.meta.code == 200) {
                            this.fillForm(response.data.data);
                            $('#myModal').modal('show');
                        }
                    }).catch(error => {
                        Swal.fire(
                            'Error',
                            error.response.data.message,
                            'error'
                        );
                    });
                },
                update() {
                    Swal.fire({
                        title: 'Please Wait...',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    axios({
                        method: 'POST',
                        url: "{{ route('employee.update', ':id') }}?_method=PUT".replace(':id', this.form.id),
                        data: this.buildForm(),
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        if (response.data.meta.code == 200) {
                            Swal.fire(
                                'Success',
                                response.data.meta.message,
                                'success'
                            ).then(() => {
                                Swal.close();
                                window.location.reload();
                            });
                        }
                    }).catch(error => {
                        if (error.response.status == 422) {
                            for (let key in error.response.data.errors) {
                                this.errors[key] = error.response.data.errors[key];
                            }
                        } else {
                            Swal.fire(
                                'Error',
                                error.response.data.message,
                                'error'
                            );
                        }
                    });
                },
                destroy(id) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            Swal.fire({
                                title: 'Please Wait...',
                                allowEscapeKey: false,
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            axios({
                                method: 'DELETE',
                                url: "{{ route('employee.destroy', ':id') }}".replace(':id', id),
                            }).then(response => {
                                if (response.data.meta.code == 200) {
                                    Swal.fire(
                                        'Deleted!',
                                        response.data.meta.message,
                                        'success'
                                    ).then(() => {
                                        Swal.close();
                                        window.location.reload();
                                    });
                                }
                            }).catch(error => {
                                Swal.fire(
                                    'Error',
                                    error.response.data.message,
                                    'error'
                                );
                            });
                        }
                    });
                }
            }
        })
    </script>
@endpush
