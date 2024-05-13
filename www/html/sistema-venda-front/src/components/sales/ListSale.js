import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faShoppingCart, faCashRegister, faCalculator, faChartLine,faTrash, faFileAlt  } from '@fortawesome/free-solid-svg-icons';
import ReactPaginate from 'react-paginate';
import './css/ListSale.css';
import { deleteSale, fetchSales } from '../../services/saleService';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import ViewSale from './ViewSale';

const ListSale = () => {
  const [sales, setSales] = useState([]);
  const [search, setSearch] = useState('');
  const [currentPage, setCurrentPage] = useState(0);
  const salesPerPage = 5;
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [saleToDelete, setSaleToDelete] = useState(null);
  const [filteredSales, setFilteredSales] = useState([]);
  const [viewModalOpen, setViewModalOpen] = useState(false); // Estado para controlar a abertura e fechamento do modal
  const [selectedSale, setSelectedSale] = useState(null); // Estado para armazenar a venda selecionada para visualização

  useEffect(() => {
    fetchSales()
      .then(response => {
        setSales(response.data.data);
        setFilteredSales(response.data.data);
      })
      .catch(error => console.error('Error fetching sales:', error));
  }, [selectedSale]);

  const handleSearchChange = (e) => {
    setSearch(e.target.value);
  };

  const handlePageClick = (data) => {
    setCurrentPage(data.selected);
  };

  const handleDeleteSale = (sale) => {
    setSaleToDelete(sale);
    setShowDeleteModal(true);
  };

  const closeViewModal = () => {
    setViewModalOpen(false);
    setSelectedSale(null);
  };

  const confirmDelete = () => {
    if (saleToDelete) {
      deleteSale(saleToDelete.id)
        .then(() => {
          const updatedSales = sales.filter(p => p.id !== saleToDelete.id);
          setSales(updatedSales);
          setFilteredSales(updatedSales);
          closeDeleteModal();
          toast.success("Venda deletada com sucesso");
        })
        .catch(error => console.error('Failed to delete sale:', error));
    }
  };

  const closeDeleteModal = () => {
    setShowDeleteModal(false);
    setSaleToDelete(null);
  };

  const today = new Date();
  const totalDailySales = sales.reduce((total, sale) => {
    const saleDate = new Date(sale.created_at);
    if (saleDate.toDateString() === today.toDateString()) {
      return total + sale.total_amount;
    }
    return total;
  }, 0);

  const pageCount = Math.ceil(filteredSales.length / salesPerPage);
  const currentItems = filteredSales.slice(
    currentPage * salesPerPage,
    (currentPage + 1) * salesPerPage
  );

  const totalSales = sales.reduce((total, sale) => total + sale.total_amount, 0);
  const totalTaxes = sales.reduce((total, sale) => total + sale.total_tax, 0);

  return (
    <div className="container mt-5">
      <div className="d-flex justify-content-content align-items-center mb-3">
        <h2>Estatísticas:</h2>
      </div>
      <div className="row mb-3 mt-1">
        <div className="col-md-4">
            <div className="card text-white mb-3" style={{ backgroundColor: '#00cdff', display: 'flex', flexDirection: 'row',          alignItems: 'center', padding: '10px' }}>
                <FontAwesomeIcon icon={faCashRegister} size="3x" style={{ marginRight: '20px', flexShrink: 0 }} />
                <div>
                    <h2 className="card-text">R$ {totalSales.toFixed(2)}</h2>
                    <h5 className="card-title">Total de Vendas</h5>
                </div>
            </div>
        </div>
        <div className="col-md-4">
            <div className="card text-white mb-3" style={{ backgroundColor: '#696969', display: 'flex', flexDirection: 'row',          alignItems: 'center', padding: '10px' }}>
                <FontAwesomeIcon icon={faCalculator} size="3x" style={{ marginRight: '20px', flexShrink: 0 }} />
                <div>
                  <h2 className="card-text">R$ {totalTaxes.toFixed(2)}</h2>
                  <h5 className="card-title">Impostos</h5>
                </div>
            </div>
        </div>
        <div className="col-md-4">
            <div className="card text-white mb-3" style={{ backgroundColor: '#ed9121', display: 'flex', flexDirection: 'row',          alignItems: 'center', padding: '10px' }}>
                <FontAwesomeIcon icon={faChartLine} size="3x" style={{ marginRight: '20px', flexShrink: 0 }} />
                <div>
                  <h2 className="card-text">{sales.length}</h2>
                  <h5 className="card-title">Qtd. Vendas</h5>
                </div>
            </div>
        </div>
      </div>
      <br/><br/>
      <div className="d-flex justify-content-center align-items-center mb-3">
        <h2>Vendas</h2>
      </div>

      <div className="table-responsive">
        <table className="table table-striped table-hover text-center">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Preço</th>
              <th scope="col">Impostos</th>
              <th scope="col">Data Criação</th>
              <th scope="col">Ações</th>
            </tr>
          </thead>
          <tbody style={{ backgroundColor: '#005780 !important', color: 'white !important' }}>
            {currentItems.map(sale => (
              <tr key={sale.id}>
                <td>{sale.id}</td>
                <td>R$ {sale.total_amount}</td>
                <td>R$ {sale.total_tax}</td>
                <td>{new Date(sale.created_at).toLocaleDateString()}</td>
                <td>
                  <button
                    className="btn btn-danger mr-2"
                    onClick={() => handleDeleteSale(sale)}
                  >
                    <FontAwesomeIcon icon={faTrash} />
                  </button>
                  {/* Abrir o modal */}
                  <Link to={`/sales/${sale.id}/view`} className="btn btn-primary mr-2">
                    <FontAwesomeIcon icon={faFileAlt} />
                  </Link>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      {
      filteredSales.length === 0 && (
        <div className="alert text-center">Nenhuma venda cadastrada.</div>
      )}
      <ReactPaginate
        previousLabel={'<'}
        nextLabel={'>'}
        pageCount={pageCount}
        onPageChange={handlePageClick}
        containerClassName={'pagination justify-content-center'}
        pageClassName={'page-item'}
        pageLinkClassName={'page-link'}
        previousClassName={'page-item'}
        previousLinkClassName={'page-link'}
        nextClassName={'page-item'}
        nextLinkClassName={'page-link'}
        activeClassName={'active'}
      />
      {showDeleteModal && (
        <div className="modal-backdrop">
          <div className="modal-content">
            <center>
              <h5>Confirmação de Exclusão</h5>
            </center>
            <center>
              <p>Deseja Excluir o produto: {saleToDelete.description}?</p>
            </center>
            <button className="btn btn-danger" onClick={confirmDelete}>
              Deletar
            </button>
            <button id='btn-close' className="btn btn-secondary" >
              Cancelar
            </button>
          </div>
        </div>
      )}
      {/* Visualizar nota */}
      {viewModalOpen && (
        <div className="modal-backdrop">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title">Visualizar Nota</h5>
              <button type="button" className="btn-close" aria-label="Close" onClick={closeViewModal}></button>
            </div>
            <div className="modal-body">
            <ViewSale saleId={selectedSale} />
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default ListSale;
