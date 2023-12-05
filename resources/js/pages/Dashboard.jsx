import { useCallback, useState } from 'react';
import {
  Banner,
  BlockStack,
  Button,
  ButtonGroup,
  Icon,
  IndexTable,
  LegacyCard,
  Page,
  Text,
  useIndexResourceState
} from '@shopify/polaris';
import API_ROUTES from '../constants/api';
import { useShop } from '../providers/ShopProvider';
import { useFilesQuery } from '../api';
import AppSpinner from '../components/AppSpinner';
import { CircleDownMajor } from '@shopify/polaris-icons';

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
  }, [info.name, status]);

  const resourceName = {
    singular: 'file',
    plural: 'files'
  };

  const {
    selectedResources,
    allResourcesSelected,
    handleSelectionChange
  } = useIndexResourceState(files);

  const rowMarkup = files.map(({ id, name, type, url }) => (
    <IndexTable.Row
      id={id}
      key={id}
      selected={selectedResources.includes(id)}
    >
      <IndexTable.Cell>
        <Text variant="bodyMd" fontWeight="bold" as="span">
          {name}
        </Text>
      </IndexTable.Cell>
      <IndexTable.Cell>{type}</IndexTable.Cell>
      <IndexTable.Cell>
        <ButtonGroup>
          <Button url={url}>
            <Icon source={CircleDownMajor} />
          </Button>
        </ButtonGroup>
      </IndexTable.Cell>
    </IndexTable.Row>
  ));

  if (isFetching) {
    return (
      <Page fullWidth>
        <AppSpinner />
      </Page>
    );
  }
  console.log(files);
  return (
    <Page fullWidth>
      <LegacyCard title="Welcome to App Partner">
        <LegacyCard.Section>
          <Text>Here we go!</Text>
        </LegacyCard.Section>

        <LegacyCard.Section></LegacyCard.Section>
      </LegacyCard>
      <LegacyCard>
        <LegacyCard.Section>
          <BlockStack
            align="space-between"
            inlineAlign="start"
            gap={400}
          >
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
      {!status && (
        <LegacyCard>
          <Banner tone="critical">
            <Text>
              Unauthorized. Please click button connect to access your
              file .
            </Text>
          </Banner>
        </LegacyCard>
      )}
      <LegacyCard>
        <IndexTable
          resourceName={resourceName}
          itemCount={files.length}
          selectedItemsCount={
            allResourcesSelected ? 'All' : selectedResources.length
          }
          onSelectionChange={handleSelectionChange}
          headings={[
            { title: 'Name' },
            { title: 'Type' },
            { title: 'Action' }
          ]}
          selectable={false}
        >
          {rowMarkup}
        </IndexTable>
      </LegacyCard>
    </Page>
  );
}
