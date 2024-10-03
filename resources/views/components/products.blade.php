@props(['id' => 0])

<div class="py-12">

    <div class="">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

            <div  class="p-6 bg-white border-b border-gray-200">
                <table id="table">
                    <thead >
                        <tr>
                            <th>ID</th>
                            <th>Nome do Produto</th>
                            <th>Quantidade</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody >
                        <tr>
                            <td >0</td>
                            <td >teste</td>
                            <td >teste</td>
                            <td >teste</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalLongoExemplo" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoExemplo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalLongoExemplo">Cadastrar Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="GET" action="/CadastrarProduto/{{$id}}">
                    @csrf
                    @method('GET')

                    <div class="form-group">
                        <label >Nome do Produto</label>
                        <input type="text" class="form-control" name="produto" placeholder="Produto">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Quantidade</label>
                        <input type="number" class="form-control" name="quantidade" placeholder="Quantidade">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Valor</label>
                        <input type="number" class="form-control" name="valor" placeholder="Valor">
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>