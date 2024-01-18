import { Frame } from '@shopify/polaris';
import { Route, Routes } from 'react-router-dom';
import Dashboard from '../pages/Dashboard';
import AppLayout from './AppLayout';
import ToastProvider from '../hooks/useToast';

export default function AppFrame() {
  return (
    <Frame>
      <ToastProvider>
        <Routes>
          <Route path="/" element={<AppLayout />}>
            <Route path="/" element={<Dashboard />} />
          </Route>
        </Routes>
      </ToastProvider>
    </Frame>
  );
}
