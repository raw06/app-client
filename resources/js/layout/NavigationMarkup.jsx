import { Navigation } from '@shopify/polaris';
import { useLocation } from 'react-router-dom';

export default function NavigationMarkup() {
  const location = useLocation();

  const navMarketPlace = [
    {
      url: '/',
      label: 'Dashboard'
    }
  ];

  return (
    <Navigation location={location.pathname}>
      <Navigation.Section items={navMarketPlace} />
    </Navigation>
  );
}
