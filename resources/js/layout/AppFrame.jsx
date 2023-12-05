import { Frame } from '@shopify/polaris';
import { Route, Routes } from 'react-router-dom';
import Dashboard from '../pages/Dashboard';
import AppLayout from './AppLayout';

export default function AppFrame() {
  return (
    <Frame>
      <Routes>
        <Route path="/" element={<AppLayout />}>
          <Route path="/" element={<Dashboard />} />
        </Route>
      </Routes>
    </Frame>
  );
}
