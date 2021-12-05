@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Company Data</h3>
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
                                    <th>Logo</th>
                                    <th>Action</th>
                                </tr>
                                @foreach ($companies as $key => $company)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $company->name }}</td>
                                        <td>{{ $company->email }}</td>
                                        <td><img src="{{ $company->file->full_path }}" alt="" width="100"></td>
                                        <td>
                                            <a href="javascript:void(0)" v-on:click="edit('{{ $company->id }}')" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="javascript:void(0)" v-on:click="destroy('{{ $company->id }}')" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="text-left">
                            Display {{ $companies->perPage() }} of {{ $companies->total() }} Data
                        </div>
                        {{-- <div class="text-left">
                            Page {{ $companies->currentPage() }} of {{ $companies->lastPage() }}
                        </div> --}}
                        <div class="text-left">{{ $companies->links() }}</div>
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
                            <input type="text" v-model="form.website" placeholder="Input Website" class="form-control" required>
                            <div class="text-sm text-danger" v-show="errors.website.length > 0">
                                @{{ errors . website[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="file" id="formFile" v-on:change="changeFile" accept="image/*">
                            <div class="text-sm text-danger" v-show="errors.logo.length > 0">
                                @{{ errors . logo[0] }}</div>
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
                    logo: '',
                    website: ''
                },
                errors: {
                    name: [],
                    email: [],
                    logo: [],
                    website: []
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
                changeFile(e) {
                    const file = e.target.files[0];
                    this.previewImage = URL.createObjectURL(file);
                    let gambar = e.target.files;
                    if (gambar.length) {
                        this.form.logo = gambar[0];
                    }
                },
                clearForm() {
                    for (let key in this.form) {
                        this.form[key] = '';
                    }
                    $("#formFile").val('');

                },
                fillForm(data) {
                    for(let key in this.form) {
                        if (key != 'logo') {
                            this.form[key] = data[key];
                        }
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
                        url: "{{ route('company.store') }}",
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
                        url: "{{ route('company.edit', ':id') }}".replace(':id', id),
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
                        url: "{{ route('company.update', ':id') }}?_method=PUT".replace(':id', this.form.id),
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
                                url: "{{ route('company.destroy', ':id') }}".replace(':id', id),
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
