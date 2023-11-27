import React, { useCallback, useState } from 'react';
import {
  BlockStack,
  Button,
  IndexTable,
  LegacyCard,
  Page,
  Text,
  useIndexResourceState
} from '@shopify/polaris';
import API_ROUTES from '../constants/api';
import { useShop } from '../providers/ShopProvider';
import { useFilesQuery } from '../api';

export default function Dashboard() {
  const { info } = useShop();
  const [status, setStatus] = useState(true);
  const [files, setFiles] = useState([]);
  const { isFetching } = useFilesQuery({
    onSuccess: (data) => {
      if (!data.success) {
        setStatus(false);
        setFiles([]);
      } else {
        setStatus(true);
        setFiles(data.data);
      }
    }
  });

  const handleRedirectOauth = useCallback(() => {
    if (!status) {
      window.open(
        `https://ic-app.test:444/${API_ROUTES.AUTHORIZE}?shop=${info.name}`,
        '_self'
      );
    }
  }, [status]);
  if (isFetching) {
    return <Page />;
  }

  const resourceName = {
    singular: 'file',
    plural: 'files'
  };

  // const {
  //   selectedResources,
  //   allResourcesSelected,
  //   handleSelectionChange
  // } = useIndexResourceState(files);

  // const rowMarkup = files.map(({ id }, index) => (
  //   <IndexTable.Row
  //     id={id}
  //     key={id}
  //     selected={selectedResources.includes(id)}
  //     position={index}
  //   ></IndexTable.Row>
  // ));

  return (
    <Page>
      <LegacyCard title="Welcome to App Partner">
        <LegacyCard.Section>
          <Text>Here we go!</Text>
        </LegacyCard.Section>

        <LegacyCard.Section></LegacyCard.Section>
      </LegacyCard>
      <LegacyCard>
        <LegacyCard.Section>
          <BlockStack align="space-between" inlineAlign="start">
            <Text>Integration Shopify App </Text>
            <Button
              onClick={handleRedirectOauth}
              disabled={status}
              external
            >
              {!status ? 'Connect' : 'Connected'}
            </Button>
          </BlockStack>
        </LegacyCard.Section>
      </LegacyCard>
      <LegacyCard>
        <LegacyCard.Section>
          {/* <IndexTable
            resourceName={resourceName}
            itemCount={files.length}
            selectedItemsCount={
              allResourcesSelected ? 'All' : selectedResources.length
            }
            onSelectionChange={handleSelectionChange}
            headings={[]}
          >
            {rowMarkup}
          </IndexTable> */}
        </LegacyCard.Section>
      </LegacyCard>
    </Page>
  );
}
