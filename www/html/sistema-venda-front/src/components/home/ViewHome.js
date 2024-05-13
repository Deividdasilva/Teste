import React, { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faShoppingCart, faCashRegister, faCalculator, faChartLine } from '@fortawesome/free-solid-svg-icons';
import './css/Home.css';
import { fetchSales } from '../../services/saleService';
import 'react-toastify/dist/ReactToastify.css';
// import ViewHome from './ViewSale';

const ViewHome = () => {
  const [sales, setSales] = useState([]);
  const [filteredSales, setFilteredSales] = useState([]);
  const [selectedSale, setSelectedSale] = useState(null);

  useEffect(() => {
    fetchSales()
      .then(response => {
        setSales(response.data.data);
        setFilteredSales(response.data.data);
      })
      .catch(error => console.error('Error fetching sales:', error));
  }, [selectedSale]);

  const today = new Date();
  const totalDailySales = sales.reduce((total, sale) => {
    const saleDate = new Date(sale.created_at);
    if (saleDate.toDateString() === today.toDateString()) {
      return total + sale.total_amount;
    }
    return total;
  }, 0);
  const totalSales = sales.reduce((total, sale) => total + sale.total_amount, 0);
  const totalTaxes = sales.reduce((total, sale) => total + sale.total_tax, 0);

  return (
    <div className="container mt-5">
      <h3>Painel</h3><br/>
      <div className="row mb-3 mt-1">
        <div className="col-md-3">
            <div className="card text-white mb-3" style={{ backgroundColor: '#009000', display: 'flex', flexDirection: 'row',          alignItems: 'center', padding: '10px' }}>
                <FontAwesomeIcon icon={faShoppingCart} size="3x" style={{ marginRight: '20px', flexShrink: 0 }} />
                <div>
                    <h2 className="card-text">R$ {totalDailySales.toFixed(2)}</h2>
                    <h5 className="card-title">Vendas Hoje</h5>
                </div>
            </div>
        </div>
        <div className="col-md-3">
            <div className="card text-white mb-3" style={{ backgroundColor: '#00cdff', display: 'flex', flexDirection: 'row',          alignItems: 'center', padding: '10px' }}>
                <FontAwesomeIcon icon={faCashRegister} size="3x" style={{ marginRight: '20px', flexShrink: 0 }} />
                <div>
                    <h2 className="card-text">R$ {totalSales.toFixed(2)}</h2>
                    <h5 className="card-title">Total de Vendas</h5>
                </div>
            </div>
        </div>
        <div className="col-md-3">
            <div className="card text-white mb-3" style={{ backgroundColor: '#696969', display: 'flex', flexDirection: 'row',          alignItems: 'center', padding: '10px' }}>
                <FontAwesomeIcon icon={faCalculator} size="3x" style={{ marginRight: '20px', flexShrink: 0 }} />
                <div>
                  <h2 className="card-text">R$ {totalTaxes.toFixed(2)}</h2>
                  <h5 className="card-title">Impostos</h5>
                </div>
            </div>
        </div>
        <div className="col-md-3">
            <div className="card text-white mb-3" style={{ backgroundColor: '#ed9121', display: 'flex', flexDirection: 'row',          alignItems: 'center', padding: '10px' }}>
                <FontAwesomeIcon icon={faChartLine} size="3x" style={{ marginRight: '20px', flexShrink: 0 }} />
                <div>
                  <h2 className="card-text">{sales.length}</h2>
                  <h5 className="card-title">Qtd. Vendas</h5>
                </div>
            </div>
        </div>
      </div>
    </div>
  );
};

export default ViewHome;
