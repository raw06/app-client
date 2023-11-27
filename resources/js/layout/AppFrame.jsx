import React from 'react';
import { Frame } from '@shopify/polaris';
import { Route, Routes } from 'react-router-dom';
import Dashboard from '../pages/Dashboard';

export default function AppFrame() {
  return (
    <Frame>
      <Routes>
        <Route path="/" element={<Dashboard />} />
      </Routes>
    </Frame>
  );
}
